<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

/**
 * "Committee" was the wrong word: these are informal groups of parents, not
 * formal bodies. The public menu now says Action Groups and each one is a
 * Group. Only the names and slugs change; the table and code keep their
 * old names, which nobody outside sees.
 *
 * Old /committees/... links redirect to the new slugs (routes/web.php).
 */
return new class extends Migration
{
    public function up(): void
    {
        DB::table('committees')
            ->where('committee_type', 'regional')
            ->where('slug', 'east-cork-reclaim-childhood')
            ->update(['name' => 'East Cork Action Group', 'slug' => 'east-cork-action-group', 'updated_at' => now()]);

        foreach (DB::table('committees')->get(['id', 'name', 'slug']) as $row) {
            $name = preg_replace('/ Committee$/', ' Group', $row->name);
            $slug = preg_replace('/-committee$/', '-group', $row->slug);

            if ($name === $row->name && $slug === $row->slug) {
                continue;
            }

            // Never collide with a slug someone has already given a group.
            if ($slug !== $row->slug && DB::table('committees')->where('slug', $slug)->exists()) {
                $slug = $row->slug;
            }

            DB::table('committees')->where('id', $row->id)->update([
                'name' => $name,
                'slug' => $slug,
                'updated_at' => now(),
            ]);
        }
    }

    public function down(): void
    {
        DB::table('committees')
            ->where('slug', 'east-cork-action-group')
            ->update(['name' => 'East Cork Reclaim Childhood', 'slug' => 'east-cork-reclaim-childhood']);

        // School groups only: activity and event groups are named "... Group"
        // on purpose and were never committees.
        foreach (DB::table('committees')->where('committee_type', 'school')->get(['id', 'name', 'slug']) as $row) {
            DB::table('committees')->where('id', $row->id)->update([
                'name' => preg_replace('/ Group$/', ' Committee', $row->name),
                'slug' => preg_replace('/-group$/', '-committee', $row->slug),
            ]);
        }
    }
};
