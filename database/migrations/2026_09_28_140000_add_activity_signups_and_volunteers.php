<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

/**
 * Sign-ups and volunteers for activities.
 *
 * - Sign-ups: a registered parent signs up their registered children. For a
 *   weekly session one sign-up means "we'll usually come". Only registered
 *   children can be signed up, which is what makes Rebels-only events work.
 * - Volunteers: parents or supporters offering to help, e.g. marshals on the
 *   Greenway cycle. Supporters have no login, so this takes a name, email
 *   and phone rather than an account.
 * - A WhatsApp group link per activity, shown only to parents who have
 *   signed up — everyone in a WhatsApp group sees everyone's number, so the
 *   link must not be public.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('activities', function (Blueprint $table) {
            $table->boolean('signups_open')->default(false)->after('suggestions_open');
            $table->boolean('volunteers_open')->default(false)->after('signups_open');
            $table->string('volunteer_note', 255)->nullable()->after('volunteers_open');
            $table->string('whatsapp_url', 255)->nullable()->after('volunteer_note');
        });

        Schema::create('activity_signups', function (Blueprint $table) {
            $table->increments('id');

            $table->unsignedInteger('activity_id');
            $table->foreign('activity_id', 'fk_activity_signups_activity')
                ->references('id')->on('activities')->cascadeOnDelete();

            $table->unsignedInteger('child_id');
            $table->foreign('child_id', 'fk_activity_signups_child')
                ->references('id')->on('children')->cascadeOnDelete();

            // Whoever signed them up — the owner or a co-parent.
            $table->unsignedInteger('parent_id')->nullable();
            $table->foreign('parent_id', 'fk_activity_signups_parent')
                ->references('id')->on('parents')->nullOnDelete();

            $table->timestamps();

            $table->unique(['activity_id', 'child_id'], 'uq_activity_signups');
        });

        Schema::create('activity_volunteers', function (Blueprint $table) {
            $table->increments('id');

            $table->unsignedInteger('activity_id');
            $table->foreign('activity_id', 'fk_activity_volunteers_activity')
                ->references('id')->on('activities')->cascadeOnDelete();

            // Set when a logged-in parent volunteers; null for supporters.
            $table->unsignedInteger('parent_id')->nullable();
            $table->foreign('parent_id', 'fk_activity_volunteers_parent')
                ->references('id')->on('parents')->nullOnDelete();

            $table->string('name', 150);
            $table->string('email', 150);
            $table->string('phone', 40)->nullable();
            $table->string('note', 500)->nullable();
            $table->timestamps();

            $table->unique(['activity_id', 'email'], 'uq_activity_volunteers');
        });

        // The cycle needs marshals; badminton takes a one-off "we'll usually
        // come" sign-up. The party's tickets come later.
        DB::table('activities')->where('title', 'Greenway Cycle')->where('starts_on', '2026-10-18')->update([
            'signups_open' => true,
            'volunteers_open' => true,
            'volunteer_note' => 'Volunteers needed to supervise the route.',
        ]);

        DB::table('activities')->where('title', 'Badminton')->whereNull('starts_on')->update([
            'signups_open' => true,
        ]);
    }

    public function down(): void
    {
        Schema::dropIfExists('activity_volunteers');
        Schema::dropIfExists('activity_signups');

        Schema::table('activities', function (Blueprint $table) {
            $table->dropColumn(['signups_open', 'volunteers_open', 'volunteer_note', 'whatsapp_url']);
        });
    }
};
