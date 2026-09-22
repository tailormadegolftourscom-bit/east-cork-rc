<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

/**
 * Gives committees actual people.
 *
 * Until now a committee had a single `primary_contact_id` and nothing else —
 * no members, no convenor, no public page. Production has 29 committees and
 * not one of them has so much as a contact.
 *
 * Membership is polymorphic because a committee can hold both parents and
 * non-parent supporters, and those live in different tables by design.
 */
return new class extends Migration
{
    public function up(): void
    {
        $mysql = DB::getDriverName() === 'mysql';

        Schema::create('committee_members', function (Blueprint $table) {
            $table->increments('id');

            $table->unsignedInteger('committee_id');

            // Parents or Supporter — the two registers of people we hold.
            $table->string('member_type', 100);
            $table->unsignedInteger('member_id');

            // Exactly one convenor per committee, enforced in the model layer
            // rather than the schema: MySQL cannot express "at most one row
            // per committee where role = convenor".
            $table->enum('role', ['convenor', 'member'])->default('member');

            // Committee membership is public, so joining is consent to be
            // named — including for a parent who otherwise appears under an
            // anonymous code. Recorded, not assumed.
            $table->timestamp('name_consent_at')->nullable();

            $table->timestamp('joined_at')->useCurrent();
            $table->timestamps();

            $table->unique(['committee_id', 'member_type', 'member_id'], 'uq_committee_members');
            $table->index(['member_type', 'member_id'], 'ix_committee_members_member');
            $table->index(['committee_id', 'role'], 'ix_committee_members_role');
        });

        if ($mysql) {
            Schema::table('committee_members', function (Blueprint $table) {
                $table->foreign('committee_id', 'fk_committee_members_committee')
                    ->references('id')->on('committees')->cascadeOnDelete();
            });
        }

        Schema::table('committees', function (Blueprint $table) {
            // Class-level committees. Nullable because most committees are not
            // tied to a class, and they are created on demand rather than
            // spawned for all 188 classes up front.
            $table->unsignedInteger('school_class_id')->nullable()->after('school_id');

            // The convenor's own words about what the committee is for.
            $table->text('objectives')->nullable()->after('town');
        });

        if ($mysql) {
            Schema::table('committees', function (Blueprint $table) {
                $table->foreign('school_class_id', 'fk_committees_school_class')
                    ->references('id')->on('school_classes')->cascadeOnDelete();
            });
        }

        // `primary_contact_id` predates this and was never populated on
        // production — a convenor row in committee_members replaces it.
        // Left in place rather than dropped, in case anything still reads it.
    }

    public function down(): void
    {
        $mysql = DB::getDriverName() === 'mysql';

        if ($mysql) {
            Schema::table('committees', fn (Blueprint $t) => $t->dropForeign('fk_committees_school_class'));
        }

        Schema::table('committees', fn (Blueprint $t) => $t->dropColumn(['school_class_id', 'objectives']));

        Schema::dropIfExists('committee_members');
    }
};
