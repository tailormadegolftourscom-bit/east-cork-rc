<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('schools', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name', 150);
            $table->string('slug', 160)->unique();
            $table->enum('school_type', ['primary', 'secondary']);
            $table->string('address_line_1', 150)->nullable();
            $table->string('address_line_2', 150)->nullable();
            $table->string('town', 100)->nullable();
            $table->string('eircode', 20)->nullable();
            $table->string('website_url', 255)->nullable();
            $table->string('school_phone', 40)->nullable();

            $table->unsignedInteger('principal_person_id')->nullable();
            $table->foreign('principal_person_id')->references('id')->on('people')->nullOnDelete();
            $table->string('principal_name', 150)->nullable();
            $table->string('principal_email', 150)->nullable();

            $table->unsignedInteger('vice_principal_person_id')->nullable();
            $table->foreign('vice_principal_person_id')->references('id')->on('people')->nullOnDelete();
            $table->string('vice_principal_name', 150)->nullable();
            $table->string('vice_principal_email', 150)->nullable();

            $table->unsignedInteger('secretary_person_id')->nullable();
            $table->foreign('secretary_person_id')->references('id')->on('people')->nullOnDelete();
            $table->string('secretary_name', 150)->nullable();
            $table->string('secretary_email', 150)->nullable();

            $table->string('support_status', 20)->default('undecided');
            $table->enum('status', ['inactive', 'active'])->default('inactive');
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('schools');
    }
};
