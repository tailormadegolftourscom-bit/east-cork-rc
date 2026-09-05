<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('school_registration_requests', function (Blueprint $table) {
            $table->increments('id');
            $table->string('school_name', 150);
            $table->enum('school_type', ['primary', 'secondary']);
            $table->string('town', 100)->nullable();
            $table->string('website_url', 255)->nullable();
            $table->string('contact_name', 150);
            $table->string('contact_email', 150);
            $table->string('contact_phone', 40)->nullable();
            $table->text('notes')->nullable();
            $table->enum('request_status', ['pending', 'approved', 'rejected'])->default('pending');

            $table->unsignedInteger('created_school_id')->nullable();
            $table->foreign('created_school_id')->references('id')->on('schools')->nullOnDelete();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('school_registration_requests');
    }
};
