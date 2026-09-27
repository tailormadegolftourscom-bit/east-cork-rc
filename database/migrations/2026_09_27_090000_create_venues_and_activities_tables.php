<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

/**
 * Venues and the activities held at them.
 *
 * Kept in the database rather than written into the page so the convenor can
 * add an evening or change a time from the admin screen without a deploy —
 * the list is expected to change far more often than the code does.
 *
 * An activity is either planned (it is happening) or suggested (an idea
 * waiting for someone to take it on). Both are shown publicly, because a
 * suggestion is itself a call for volunteers.
 *
 * int unsigned keys to match the rest of the hand-built production schema.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('venues', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name', 150);
            $table->string('town', 100)->nullable();
            $table->string('description', 500)->nullable();
            $table->string('map_url', 255)->nullable();
            $table->unsignedInteger('sort_order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        Schema::create('activities', function (Blueprint $table) {
            $table->increments('id');
            $table->string('title', 150);
            $table->enum('status', ['planned', 'suggested'])->default('suggested');

            $table->unsignedInteger('venue_id')->nullable();
            $table->foreign('venue_id', 'fk_activities_venue')
                ->references('id')->on('venues')->nullOnDelete();

            // A one-off has a date; a weekly session has none and says when in
            // `schedule` instead. A dated activity drops off the public list
            // the day after it happens.
            $table->date('starts_on')->nullable();
            $table->string('schedule', 150)->nullable();

            $table->text('summary')->nullable();

            // Free text rather than a link to a parent record: a convenor may
            // not be registered here at all, and this is shown publicly.
            $table->string('convenor_name', 150)->nullable();

            $table->string('link_url', 255)->nullable();
            $table->string('link_label', 100)->nullable();

            $table->unsignedInteger('sort_order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->index(['is_active', 'starts_on'], 'ix_activities_active_starts');
        });

        $this->seed();
    }

    /** The first venues and activities, as agreed on 27 September 2026. */
    private function seed(): void
    {
        $now = now();

        $venue = fn (string $name, ?string $town, int $sort) => DB::table('venues')->insertGetId([
            'name' => $name,
            'town' => $town,
            'sort_order' => $sort,
            'is_active' => true,
            'created_at' => $now,
            'updated_at' => $now,
        ]);

        $communityCentre = $venue('Midleton Community Centre', 'Midleton', 10);
        $greenway = $venue('Midleton–Youghal Greenway', 'Midleton', 20);
        $venue('My Place Midleton', 'Midleton', 30);
        $venue('Ladysbridge Community Hall', 'Ladysbridge', 40);

        DB::table('activities')->insert([
            [
                'title' => 'Badminton',
                'status' => 'planned',
                'venue_id' => $communityCentre,
                'starts_on' => null,
                'schedule' => 'Every Monday, 3–5pm',
                'summary' => 'Weekly badminton for Gen Alpha Rebels. Ask the convenor about joining.',
                'convenor_name' => "Peter O'Sullivan",
                'link_url' => null,
                'link_label' => null,
                'sort_order' => 10,
                'is_active' => true,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'title' => 'Greenway Cycle for CyberBreak',
                'status' => 'planned',
                'venue_id' => $greenway,
                'starts_on' => '2026-10-17',
                'schedule' => 'Time and meeting point to be confirmed',
                'summary' => 'A family cycle on the Greenway during CyberBreak, the CyberSafeKids national '
                    .'24-hour switch-off on 16–17 October.',
                'convenor_name' => "Peter O'Sullivan",
                'link_url' => 'https://www.cybersafekids.ie/cyberbreak/',
                'link_label' => 'About CyberBreak',
                'sort_order' => 20,
                'is_active' => true,
                'created_at' => $now,
                'updated_at' => $now,
            ],
        ]);
    }

    public function down(): void
    {
        Schema::dropIfExists('activities');
        Schema::dropIfExists('venues');
    }
};
