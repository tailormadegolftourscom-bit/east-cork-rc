<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * A 6th class parent may name a secondary school that is not listed yet.
 *
 * Held as typed text until that school is added, at which point creating it
 * from its request links these children to it by name and clears this.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('child_school_links', function (Blueprint $table) {
            $table->string('unlisted_secondary_name', 150)->nullable()->after('likely_secondary_school_id');
        });
    }

    public function down(): void
    {
        Schema::table('child_school_links', function (Blueprint $table) {
            $table->dropColumn('unlisted_secondary_name');
        });
    }
};
