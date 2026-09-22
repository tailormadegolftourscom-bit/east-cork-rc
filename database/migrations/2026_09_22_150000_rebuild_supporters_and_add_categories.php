<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

/**
 * Rebuilds `supporters` as a register in its own right.
 *
 * It used to mean "this parent opted in", which is now implied: every parent
 * is a supporter, so a separate opt-in row for them says nothing. The table
 * instead holds the people who back the initiative but are not parents here —
 * teachers with ideas, celebrities offering an endorsement, neighbours willing
 * to help — who have no children in the system and need no login.
 *
 * Categories are deliberately a table rather than an enum, so new ones can be
 * added from the admin screen without a migration, and they attach
 * polymorphically: a parent who is also a teacher or willing to volunteer is
 * likely, and should be one record with tags rather than a duplicate person
 * in two tables.
 */
return new class extends Migration
{
    private const CATEGORIES = [
        ['slug' => 'armchair',   'name' => 'Armchair Supporter',  'description' => 'Backs the initiative and wants to be counted.',                 'sort_order' => 10],
        ['slug' => 'volunteer',  'name' => 'Willing to Volunteer','description' => 'Happy to help supervise kids activities.',                      'sort_order' => 20],
        ['slug' => 'teacher',    'name' => 'Teacher',             'description' => 'Works in education and has ideas to contribute.',               'sort_order' => 30],
        ['slug' => 'celebrity',  'name' => 'Celebrity',           'description' => 'Endorsement, a message for the kids, personal appearances.',    'sort_order' => 40],
        ['slug' => 'updates',    'name' => 'Keep Me Updated',     'description' => 'Wants news only — the old /updates mailing list.',              'sort_order' => 50],
    ];

    public function up(): void
    {
        $mysql = DB::getDriverName() === 'mysql';

        // Parents carry their support implicitly now, so the old rows say
        // nothing the parents table does not already say.
        if ($mysql && Schema::hasTable('supporters')) {
            Schema::table('supporters', function (Blueprint $t) {
                $t->dropForeign('fk_supporters_person');
            });
        }

        Schema::dropIfExists('supporters');

        Schema::create('supporters', function (Blueprint $table) {
            $table->increments('id');

            $table->string('first_name', 100);
            $table->string('last_name', 100);
            $table->string('email', 150)->unique('uq_supporters_email');
            $table->string('phone', 40)->nullable();

            // What they said when signing up — the useful part of a celebrity
            // or teacher offer, and worth reading before anyone replies.
            $table->text('message')->nullable();

            $table->enum('preferred_contact_method', ['email', 'sms', 'whatsapp'])->default('email');
            $table->boolean('is_active')->default(true);
            $table->timestamp('joined_at')->useCurrent();
            $table->timestamps();
        });

        Schema::create('supporter_categories', function (Blueprint $table) {
            $table->increments('id');
            $table->string('slug', 50)->unique('uq_supporter_categories_slug');
            $table->string('name', 100);
            $table->string('description', 255)->nullable();
            $table->unsignedInteger('sort_order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        // Polymorphic: the same category list serves parents and supporters,
        // so a parent who is also a teacher stays one record.
        Schema::create('supporter_category_links', function (Blueprint $table) {
            $table->increments('id');

            $table->unsignedInteger('supporter_category_id');
            $table->string('linkable_type', 100);
            $table->unsignedInteger('linkable_id');
            $table->timestamps();

            $table->unique(
                ['supporter_category_id', 'linkable_type', 'linkable_id'],
                'uq_supporter_category_links'
            );
            $table->index(['linkable_type', 'linkable_id'], 'ix_supporter_category_links_linkable');
        });

        if ($mysql) {
            Schema::table('supporter_category_links', function (Blueprint $table) {
                $table->foreign('supporter_category_id', 'fk_supporter_category_links_category')
                    ->references('id')->on('supporter_categories')->cascadeOnDelete();
            });
        }

        $now = now();

        DB::table('supporter_categories')->insert(array_map(
            fn (array $c) => $c + ['is_active' => true, 'created_at' => $now, 'updated_at' => $now],
            self::CATEGORIES
        ));

        $this->absorbSubscribers();

        // Parents finish onboarding by choosing contact preferences on
        // /parent/start. That used to be recorded by the existence of a
        // supporter row; with those gone it needs a column of its own.
        // Distinct from registration_completed_at, which is about setting a
        // password — someone can do one without the other.
        Schema::table('parents', function (Blueprint $table) {
            $table->timestamp('onboarded_at')->nullable()->after('registration_completed_at');

            // Moves across from the old supporters table, which carried it
            // while "supporter" still meant "registered parent".
            $table->timestamp('no_children_reminder_sent_at')->nullable()->after('onboarded_at');

            // How many nudges an unfinished registration has had. Counting
            // rather than timestamping keeps the 3/6/9 schedule readable and
            // survives the sweep not running on a given day.
            $table->unsignedTinyInteger('pending_reminder_count')->default(0)->after('no_children_reminder_sent_at');
            $table->timestamp('pending_reminder_sent_at')->nullable()->after('pending_reminder_count');

            // The sweep counts from here, not created_at, so re-inviting
            // someone restarts their 3/6/9 clock instead of deleting them on
            // the next run because the original invite went stale.
            $table->timestamp('invited_at')->nullable()->after('pending_reminder_sent_at');
        });

        DB::table('parents')->update(['invited_at' => DB::raw('created_at')]);

        $this->purgeOrphanRows();

        // Anyone who had a supporter row had been through that form.
        DB::table('parents')
            ->whereNotNull('registration_completed_at')
            ->update(['onboarded_at' => DB::raw('registration_completed_at')]);
    }

    /**
     * Clears out rows that carry no login and nothing depending on them —
     * the debris of the old person/login split, where deleting an account
     * left the person record standing.
     *
     * These must go before the pending-registration sweep starts running, or
     * it would post reminders to people whose accounts were already deleted,
     * and offer them a password-reset link that would hand them a working
     * account they never asked for.
     */
    private function purgeOrphanRows(): void
    {
        $referenced = collect()
            ->merge(DB::table('children')->pluck('parent_id'))
            ->merge(DB::table('child_guardians')->pluck('parent_id'))
            ->merge(DB::table('schools')->pluck('principal_id'))
            ->merge(DB::table('schools')->pluck('vice_principal_id'))
            ->merge(DB::table('schools')->pluck('secretary_id'))
            ->merge(DB::table('committees')->pluck('primary_contact_id'))
            ->filter()
            ->unique()
            ->values()
            ->all();

        DB::table('parents')
            // No password at all means there was never a login here, only a
            // leftover contact record.
            ->whereNull('password')
            ->when($referenced, fn ($q) => $q->whereNotIn('id', $referenced))
            ->delete();
    }

    /**
     * `/updates` was an email-only mailing list — the same idea as an armchair
     * supporter with less information. Folded in so there is one register
     * rather than two half-registers.
     */
    private function absorbSubscribers(): void
    {
        if (! Schema::hasTable('subscribers')) {
            return;
        }

        $updatesId = DB::table('supporter_categories')->where('slug', 'updates')->value('id');

        foreach (DB::table('subscribers')->orderBy('id')->get() as $sub) {
            if (DB::table('supporters')->where('email', $sub->email)->exists()) {
                continue;
            }

            $id = DB::table('supporters')->insertGetId([
                // A bare email is all the old list ever captured.
                'first_name' => 'Unknown',
                'last_name' => 'Subscriber',
                'email' => $sub->email,
                'is_active' => true,
                'joined_at' => $sub->created_at ?? now(),
                'created_at' => $sub->created_at ?? now(),
                'updated_at' => $sub->updated_at ?? now(),
            ]);

            DB::table('supporter_category_links')->insert([
                'supporter_category_id' => $updatesId,
                'linkable_type' => \App\Models\Supporter::class,
                'linkable_id' => $id,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        Schema::dropIfExists('subscribers');
    }

    public function down(): void
    {
        throw new RuntimeException(
            'Irreversible: the old supporters table recorded parent opt-ins that no longer exist as a concept.'
        );
    }
};
