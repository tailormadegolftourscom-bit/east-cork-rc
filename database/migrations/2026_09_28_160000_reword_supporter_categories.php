<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

/**
 * The supporter category descriptions are shown under each tick box on the
 * public "Join as a Supporter" form, but were written as notes for admin —
 * one even mentioned "the old /updates mailing list". Reworded to speak to
 * the visitor. Only descriptions still in their original wording change.
 */
return new class extends Migration
{
    private const WORDING = [
        'armchair' => ['Backs the initiative and wants to be counted.', 'Count me in — I back the initiative.'],
        'volunteer' => ['Happy to help supervise kids activities.', 'I can help at activities, like marshalling on the Greenway cycle.'],
        'teacher' => ['Works in education and has ideas to contribute.', 'I work in education and have ideas to share.'],
        'updates' => ['Wants news only — the old /updates mailing list.', 'Just send me news now and then.'],
    ];

    public function up(): void
    {
        foreach (self::WORDING as $slug => [$old, $new]) {
            DB::table('supporter_categories')
                ->where('slug', $slug)
                ->where('description', $old)
                ->update(['description' => $new, 'updated_at' => now()]);
        }
    }

    public function down(): void
    {
        foreach (self::WORDING as $slug => [$old, $new]) {
            DB::table('supporter_categories')
                ->where('slug', $slug)
                ->where('description', $new)
                ->update(['description' => $old]);
        }
    }
};
