<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

/**
 * The Greenway cycle moves from Saturday 17 to Sunday 18 October at 1pm —
 * the day after CyberBreak rather than during it, so it no longer carries
 * the CyberBreak name.
 *
 * Matches the seeded row by its original title and date, so an activity
 * that has already been edited from the admin screen is left alone.
 */
return new class extends Migration
{
    public function up(): void
    {
        DB::table('activities')
            ->where('title', 'Greenway Cycle for CyberBreak')
            ->where('starts_on', '2026-10-17')
            ->update([
                'title' => 'Greenway Cycle',
                'starts_on' => '2026-10-18',
                'schedule' => '1:00pm · meeting point to be confirmed',
                'summary' => 'A family cycle on the Greenway to round off the weekend of CyberBreak, the '
                    .'CyberSafeKids national 24-hour switch-off on 16–17 October.',
                'updated_at' => now(),
            ]);
    }

    public function down(): void
    {
        DB::table('activities')
            ->where('title', 'Greenway Cycle')
            ->where('starts_on', '2026-10-18')
            ->update([
                'title' => 'Greenway Cycle for CyberBreak',
                'starts_on' => '2026-10-17',
                'schedule' => 'Time and meeting point to be confirmed',
                'summary' => 'A family cycle on the Greenway during CyberBreak, the CyberSafeKids national '
                    .'24-hour switch-off on 16–17 October.',
                'updated_at' => now(),
            ]);
    }
};
