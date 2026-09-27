<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

/**
 * The first activity and event groups, as agreed on 28 September 2026:
 * Midleton Badminton Group and Monster Party 2027 Group.
 *
 * Both sit under the East Cork Action Group and are convened by whoever
 * convenes that one (Peter). Assigned rather than volunteered, so
 * name_consent_at stays null and his own display setting applies — the same
 * rule as assigning a convenor from the admin screen.
 */
return new class extends Migration
{
    private const GROUPS = [
        ['name' => 'Midleton Badminton Group', 'slug' => 'midleton-badminton-group', 'type' => 'activity', 'town' => 'Midleton'],
        ['name' => 'Monster Party 2027 Group', 'slug' => 'monster-party-2027-group', 'type' => 'event', 'town' => null],
    ];

    public function up(): void
    {
        $regional = DB::table('committees')->where('committee_type', 'regional')->first();

        $convenor = $regional
            ? DB::table('committee_members')->where('committee_id', $regional->id)->where('role', 'convenor')->first()
            : null;

        $now = now();

        foreach (self::GROUPS as $group) {
            if (DB::table('committees')->where('slug', $group['slug'])->exists()) {
                continue;
            }

            $id = DB::table('committees')->insertGetId([
                'area_id' => $regional->area_id ?? null,
                'name' => $group['name'],
                'slug' => $group['slug'],
                'committee_type' => $group['type'],
                'parent_committee_id' => $regional->id ?? null,
                'town' => $group['town'],
                'status' => 'active',
                'created_at' => $now,
                'updated_at' => $now,
            ]);

            if ($convenor) {
                DB::table('committee_members')->insert([
                    'committee_id' => $id,
                    'member_type' => $convenor->member_type,
                    'member_id' => $convenor->member_id,
                    'role' => 'convenor',
                    'name_consent_at' => null,
                    'joined_at' => $now,
                    'created_at' => $now,
                    'updated_at' => $now,
                ]);
            }
        }
    }

    public function down(): void
    {
        $ids = DB::table('committees')->whereIn('slug', array_column(self::GROUPS, 'slug'))->pluck('id');

        DB::table('committee_members')->whereIn('committee_id', $ids)->delete();
        DB::table('committees')->whereIn('id', $ids)->delete();
    }
};
