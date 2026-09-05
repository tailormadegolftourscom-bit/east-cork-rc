<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('schools', function (Blueprint $table) {
            $table->string('alternative_name', 150)->nullable()->after('name');
            $table->string('roll_number', 20)->nullable()->unique()->after('slug');
        });
    }

    public function down(): void
    {
        Schema::table('schools', function (Blueprint $table) {
            $table->dropUnique(['roll_number']);
            $table->dropColumn(['alternative_name', 'roll_number']);
        });
    }
};
