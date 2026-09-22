<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

/**
 * Collapses `users` + `people` into a single `parents` table.
 *
 * "Person" was too generic and the split meant every human could exist as
 * up to three rows (person / login / supporter) that drifted apart — four of
 * six person rows on production had no login at all. One table, one row per
 * human.
 *
 * `parents.id` deliberately inherits `people.id` (int unsigned), because six
 * foreign keys already point at it — children, child_guardians and schools x3
 * — against one that points at users.id (bigint). Moving the single audit-log
 * column is far cheaper than widening six, and it keeps the int-unsigned PK
 * convention the rest of the hand-built schema uses.
 */
return new class extends Migration
{
    /**
     * FKs that currently reference people.id. Each is dropped, its column
     * renamed off the "person" vocabulary, then re-pointed at parents.
     *
     * `supporters` is left alone: it is rebuilt from scratch for non-parent
     * supporters in a later migration, so renaming its column here would be
     * thrown away.
     */
    private array $peopleFks = [
        ['table' => 'children',        'column' => 'parent_person_id',         'rename' => 'parent_id',           'null' => false, 'fk' => 'fk_children_parent',        'onDelete' => 'cascade'],
        ['table' => 'child_guardians', 'column' => 'person_id',                'rename' => 'parent_id',           'null' => false, 'fk' => 'fk_child_guardians_person', 'onDelete' => 'cascade'],
        ['table' => 'supporters',      'column' => 'person_id',                'rename' => null,                  'null' => false, 'fk' => 'fk_supporters_person',      'onDelete' => 'cascade'],
        ['table' => 'schools',         'column' => 'principal_person_id',      'rename' => 'principal_id',        'null' => true,  'fk' => 'fk_schools_principal',      'onDelete' => 'set null'],
        ['table' => 'schools',         'column' => 'vice_principal_person_id', 'rename' => 'vice_principal_id',   'null' => true,  'fk' => 'fk_schools_vice_principal', 'onDelete' => 'set null'],
        ['table' => 'schools',         'column' => 'secretary_person_id',      'rename' => 'secretary_id',        'null' => true,  'fk' => 'fk_schools_secretary',      'onDelete' => 'set null'],
    ];

    public function up(): void
    {
        $mysql = DB::getDriverName() === 'mysql';

        // Only MySQL gets the FK rewiring below — SQLite cannot drop a foreign
        // key at all, so on that driver the old constraints still point at
        // `people` when it is dropped. Run local SQLite tests with
        // DB_FOREIGN_KEYS=false; the constraint work is verified against MySQL,
        // which is the only place it actually executes.
        $this->guardEmailLengths();

        Schema::create('parents', function (Blueprint $table) {
            $table->increments('id');

            // Identity (was `people`)
            $table->string('first_name', 100)->nullable();
            $table->string('last_name', 100)->nullable();
            $table->string('email', 150)->unique('uq_parents_email');
            $table->string('phone', 40)->nullable();
            $table->enum('public_name_mode', ['real_name', 'anon_code'])->default('real_name');
            $table->enum('preferred_contact_method', ['email', 'sms', 'whatsapp'])->default('email');
            $table->timestamp('phone_verified_at')->nullable();

            // Login (was `users`). Nullable throughout: a row may be a contact
            // record with no account at all, e.g. a school principal.
            $table->string('name')->nullable();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password')->nullable();
            $table->text('two_factor_secret')->nullable();
            $table->text('two_factor_recovery_codes')->nullable();
            $table->timestamp('two_factor_confirmed_at')->nullable();
            $table->boolean('is_admin')->default(false);
            $table->enum('user_type', ['admin', 'school', 'parent'])->default('parent');
            $table->timestamp('suspended_at')->nullable();
            $table->unsignedInteger('school_id')->nullable();
            $table->rememberToken();

            // Set the first time someone successfully sets their own password.
            // email_verified_at cannot serve as this signal: co-parent invites
            // arrive pre-verified, so a co-parent can be "verified" and still
            // have never chosen a password or logged in.
            $table->timestamp('registration_completed_at')->nullable();

            $table->timestamps();
        });

        if ($mysql) {
            Schema::table('parents', function (Blueprint $table) {
                $table->foreign('school_id', 'fk_parents_school')
                    ->references('id')->on('schools')->nullOnDelete();
            });
        }

        $idMap = $this->migrateRows();

        $this->repointPeopleForeignKeys($mysql);
        $this->repointAuditLog($mysql, $idMap);

        // Holds person ids but was never given a foreign key, so it needs no
        // constraint work — just the rename off the "person" vocabulary. Its
        // values stay valid because parent ids inherit person ids.
        if (Schema::hasColumn('committees', 'primary_contact_person_id')) {
            $mysql
                ? DB::statement('ALTER TABLE `committees` CHANGE `primary_contact_person_id` `primary_contact_id` INT UNSIGNED NULL')
                : Schema::table('committees', fn (Blueprint $t) => $t->renameColumn('primary_contact_person_id', 'primary_contact_id'));
        }

        // Sessions key off the old users.id. Rather than remap them, clear
        // them: an auth-table swap should end every existing session anyway.
        if (Schema::hasTable('sessions')) {
            DB::table('sessions')->delete();
        }

        if ($mysql) {
            Schema::table('users', fn (Blueprint $t) => $t->dropForeign('fk_users_person'));
        }

        Schema::dropIfExists('users');
        Schema::dropIfExists('people');
    }

    /**
     * parents.email is varchar(150) (people's width). users.email was wider,
     * so fail loudly rather than silently truncating a real login.
     */
    private function guardEmailLengths(): void
    {
        $seen = [];

        foreach (['people', 'users'] as $table) {
            if (! Schema::hasTable($table)) {
                continue;
            }

            foreach (DB::table($table)->get(['id', 'email', ...($table === 'users' ? ['person_id'] : [])]) as $row) {
                if (mb_strlen((string) $row->email) > 150) {
                    throw new RuntimeException(
                        "Cannot merge: {$table} #{$row->id} has an email longer than 150 characters."
                    );
                }

                // parents.email is unique. A login folding into its own person
                // row reuses that row's email, so only flag genuine clashes.
                $key = mb_strtolower(trim((string) $row->email));
                $foldsIntoPerson = $table === 'users' && ! empty($row->person_id);

                if (isset($seen[$key]) && ! $foldsIntoPerson) {
                    throw new RuntimeException(
                        "Cannot merge: email {$row->email} appears in both `{$seen[$key]}` and `{$table}`."
                    );
                }

                $seen[$key] ??= $table;
            }
        }
    }

    /**
     * @return array<int,int> old users.id => new parents.id
     */
    private function migrateRows(): array
    {
        // 1. Every person becomes a parent, keeping its id so the six
        //    foreign keys that reference it stay valid.
        foreach (DB::table('people')->orderBy('id')->get() as $p) {
            DB::table('parents')->insert([
                'id' => $p->id,
                'first_name' => $p->first_name,
                'last_name' => $p->last_name,
                'email' => $p->email,
                'phone' => $p->phone,
                'public_name_mode' => $p->public_name_mode,
                'preferred_contact_method' => $p->preferred_contact_method,
                'phone_verified_at' => $p->phone_verified_at,
                'user_type' => 'parent',
                'created_at' => $p->created_at,
                'updated_at' => $p->updated_at,
            ]);
        }

        $idMap = [];

        foreach (DB::table('users')->orderBy('id')->get() as $u) {
            $login = [
                'name' => $u->name,
                'email_verified_at' => $u->email_verified_at,
                'password' => $u->password,
                'two_factor_secret' => $u->two_factor_secret,
                'two_factor_recovery_codes' => $u->two_factor_recovery_codes,
                'two_factor_confirmed_at' => $u->two_factor_confirmed_at,
                'is_admin' => $u->is_admin,
                'user_type' => $u->user_type,
                'suspended_at' => $u->suspended_at,
                'school_id' => $u->school_id,
                'remember_token' => $u->remember_token,
                // Anyone with a login already is treated as complete; the
                // 3/6/9 sweep only ever applies to accounts created from here on.
                'registration_completed_at' => $u->created_at,
            ];

            if ($u->person_id && DB::table('parents')->where('id', $u->person_id)->exists()) {
                // 2. Fold the login into the person row it already pointed at.
                DB::table('parents')->where('id', $u->person_id)->update($login);
                $idMap[$u->id] = (int) $u->person_id;

                continue;
            }

            // 3. Admin and school logins have no person row, so they get a
            //    fresh id above the person id space. A school account's name
            //    is an institution ("X School User"), not a person's, so it
            //    keeps `name` only rather than being split into a surname.
            $names = $u->user_type === 'school' ? [null, null] : $this->splitName($u->name);

            $newId = DB::table('parents')->insertGetId($login + [
                'first_name' => $names[0],
                'last_name' => $names[1],
                'email' => $u->email,
                'public_name_mode' => 'real_name',
                'preferred_contact_method' => 'email',
                'created_at' => $u->created_at,
                'updated_at' => $u->updated_at,
            ]);

            $idMap[$u->id] = (int) $newId;
        }

        return $idMap;
    }

    /** @return array{0:?string,1:?string} */
    private function splitName(?string $name): array
    {
        $name = trim((string) $name);

        if ($name === '') {
            return [null, null];
        }

        $parts = preg_split('/\s+/', $name);
        $first = array_shift($parts);

        return [$first, $parts ? implode(' ', $parts) : null];
    }

    private function repointPeopleForeignKeys(bool $mysql): void
    {
        // Every constraint comes off first: MySQL will not rename a column
        // while a foreign key still references it.
        if ($mysql) {
            foreach ($this->peopleFks as $fk) {
                Schema::table($fk['table'], fn (Blueprint $t) => $t->dropForeign($fk['fk']));
            }
        }

        // Renames run on every driver, so the models see the same column
        // names locally as they will in production.
        foreach ($this->peopleFks as $fk) {
            if (! $fk['rename']) {
                continue;
            }

            if ($mysql) {
                $nullability = $fk['null'] ? 'NULL' : 'NOT NULL';

                DB::statement(
                    "ALTER TABLE `{$fk['table']}` CHANGE `{$fk['column']}` `{$fk['rename']}` INT UNSIGNED {$nullability}"
                );

                continue;
            }

            Schema::table($fk['table'], fn (Blueprint $t) => $t->renameColumn($fk['column'], $fk['rename']));
        }

        if (! $mysql) {
            return;
        }

        foreach ($this->peopleFks as $fk) {
            $column = $fk['rename'] ?: $fk['column'];

            Schema::table($fk['table'], function (Blueprint $t) use ($fk, $column) {
                $constraint = $t->foreign($column, $fk['fk'])->references('id')->on('parents');

                $fk['onDelete'] === 'cascade'
                    ? $constraint->cascadeOnDelete()
                    : $constraint->nullOnDelete();
            });
        }
    }

    /**
     * admin_audit_log.actor_user_id is bigint and references users.id. Narrow
     * it to int unsigned and remap its values onto the new parent ids.
     *
     * @param  array<int,int>  $idMap
     */
    private function repointAuditLog(bool $mysql, array $idMap): void
    {
        if (! Schema::hasTable('admin_audit_log')) {
            return;
        }

        if ($mysql) {
            Schema::table('admin_audit_log', fn (Blueprint $t) => $t->dropForeign('admin_audit_log_actor_user_id_foreign'));
        }

        // Remapped row by row, keyed on the log's own primary key. A
        // set-based update would corrupt the mapping whenever an old user id
        // collides with a new parent id (e.g. user 9 -> parent 8 while user 8
        // still exists), and the column is unsigned so the usual
        // negative-marker trick is not available.
        foreach (DB::table('admin_audit_log')->whereNotNull('actor_user_id')->get(['id', 'actor_user_id']) as $row) {
            DB::table('admin_audit_log')
                ->where('id', $row->id)
                ->update(['actor_user_id' => $idMap[(int) $row->actor_user_id] ?? null]);
        }

        if ($mysql) {
            // Narrowed from bigint and renamed in one statement.
            DB::statement('ALTER TABLE `admin_audit_log` CHANGE `actor_user_id` `actor_id` INT UNSIGNED NULL');

            Schema::table('admin_audit_log', function (Blueprint $t) {
                $t->foreign('actor_id', 'fk_audit_actor')
                    ->references('id')->on('parents')->nullOnDelete();
            });

            return;
        }

        Schema::table('admin_audit_log', fn (Blueprint $t) => $t->renameColumn('actor_user_id', 'actor_id'));
    }

    public function down(): void
    {
        throw new RuntimeException(
            'Irreversible: users and people are merged into parents. Restore from the pre-migration backup instead.'
        );
    }
};
