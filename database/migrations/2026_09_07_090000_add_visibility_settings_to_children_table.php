<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('children', function (Blueprint $table) {
            $table->enum('identity_visibility', ['code_name', 'code_name_first_name'])
                ->default('code_name')
                ->after('public_label');

            $table->enum('class_visibility', ['general', 'specific'])
                ->default('general')
                ->after('identity_visibility');
        });
    }

    public function down(): void
    {
        Schema::table('children', function (Blueprint $table) {
            $table->dropColumn(['identity_visibility', 'class_visibility']);
        });
    }
};
