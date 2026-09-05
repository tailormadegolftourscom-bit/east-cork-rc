<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('children', function (Blueprint $table) {
            $table->increments('id');

            $table->unsignedInteger('parent_person_id');
            $table->foreign('parent_person_id')->references('id')->on('people')->cascadeOnDelete();

            $table->string('first_name', 100);
            $table->string('last_name', 100)->nullable();
            $table->string('public_label', 50)->default('anonymous');
            $table->enum('audit_status', ['pending', 'reviewed', 'verified'])->default('pending');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('children');
    }
};
