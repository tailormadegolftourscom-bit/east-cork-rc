<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('schools', function (Blueprint $table) {
            $table->unsignedInteger('area_id')->nullable()->after('id');
            $table->foreign('area_id')->references('id')->on('areas')->nullOnDelete();
        });

        Schema::table('committees', function (Blueprint $table) {
            $table->unsignedInteger('area_id')->nullable()->after('id');
            $table->foreign('area_id')->references('id')->on('areas')->nullOnDelete();
        });

        $eastCorkId = DB::table('areas')->where('slug', 'east-cork')->value('id');

        if ($eastCorkId) {
            DB::table('schools')->update(['area_id' => $eastCorkId]);
            DB::table('committees')->update(['area_id' => $eastCorkId]);
        }
    }

    public function down(): void
    {
        Schema::table('schools', function (Blueprint $table) {
            $table->dropForeign(['area_id']);
            $table->dropColumn('area_id');
        });

        Schema::table('committees', function (Blueprint $table) {
            $table->dropForeign(['area_id']);
            $table->dropColumn('area_id');
        });
    }
};
