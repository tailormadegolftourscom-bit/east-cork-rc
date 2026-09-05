<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->unsignedInteger('person_id')->nullable()->after('id');
            $table->foreign('person_id')->references('id')->on('people')->nullOnDelete();

            $table->boolean('is_admin')->default(false)->after('password');
            $table->enum('user_type', ['admin', 'school', 'parent'])->default('parent')->after('is_admin');

            $table->unsignedInteger('school_id')->nullable()->after('user_type');
            $table->foreign('school_id')->references('id')->on('schools')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['person_id']);
            $table->dropForeign(['school_id']);
            $table->dropColumn(['person_id', 'is_admin', 'user_type', 'school_id']);
        });
    }
};
