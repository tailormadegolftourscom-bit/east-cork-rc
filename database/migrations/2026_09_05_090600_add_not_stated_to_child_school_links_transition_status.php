<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (DB::getDriverName() === 'mysql') {
            DB::statement("ALTER TABLE child_school_links MODIFY COLUMN transition_status ENUM('not_applicable', 'considering', 'likely', 'confirmed', 'not_stated') NOT NULL DEFAULT 'not_applicable'");

            return;
        }

        Schema::table('child_school_links', function (Blueprint $table) {
            $table->enum('transition_status', ['not_applicable', 'considering', 'likely', 'confirmed', 'not_stated'])
                ->default('not_applicable')
                ->change();
        });
    }

    public function down(): void
    {
        DB::table('child_school_links')->where('transition_status', 'not_stated')->update(['transition_status' => 'not_applicable']);

        if (DB::getDriverName() === 'mysql') {
            DB::statement("ALTER TABLE child_school_links MODIFY COLUMN transition_status ENUM('not_applicable', 'considering', 'likely', 'confirmed') NOT NULL DEFAULT 'not_applicable'");

            return;
        }

        Schema::table('child_school_links', function (Blueprint $table) {
            $table->enum('transition_status', ['not_applicable', 'considering', 'likely', 'confirmed'])
                ->default('not_applicable')
                ->change();
        });
    }
};
