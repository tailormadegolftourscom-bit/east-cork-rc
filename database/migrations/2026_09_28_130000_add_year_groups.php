<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

/**
 * Year groups: one group for a whole year in a school — all of 6th Class,
 * say — between the school group and any individual class groups. It is the
 * default level for a year; a single class gets its own group below it only
 * where one is wanted.
 *
 * Starts with GSMNC Rang 6 Group, covering Rang 6 Ellen, Lúsaí and Neasa at
 * Gaelscoil Mhainistir na Corann. Left without a convenor for a parent in
 * the year to take on.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('committees', function (Blueprint $table) {
            $table->string('class_level', 30)->nullable()->after('school_class_id');
        });

        $schoolGroup = DB::table('committees')
            ->where('committee_type', 'school')
            ->where('slug', 'gsmnc-group')
            ->first();

        if (! $schoolGroup || DB::table('committees')->where('slug', 'gsmnc-rang-6-group')->exists()) {
            return;
        }

        DB::table('committees')->insert([
            'area_id' => $schoolGroup->area_id,
            'name' => 'GSMNC Rang 6 Group',
            'slug' => 'gsmnc-rang-6-group',
            'committee_type' => 'year',
            'parent_committee_id' => $schoolGroup->id,
            'school_id' => $schoolGroup->school_id,
            'class_level' => '6th_class',
            'town' => $schoolGroup->town,
            'status' => 'active',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    public function down(): void
    {
        DB::table('committees')->where('slug', 'gsmnc-rang-6-group')->delete();

        Schema::table('committees', function (Blueprint $table) {
            $table->dropColumn('class_level');
        });
    }
};
