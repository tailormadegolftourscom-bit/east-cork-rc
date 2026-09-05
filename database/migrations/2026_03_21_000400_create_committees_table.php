<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('committees', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name', 150);
            $table->string('slug', 160)->unique();
            $table->string('committee_type', 50);

            $table->unsignedInteger('parent_committee_id')->nullable();
            $table->foreign('parent_committee_id')->references('id')->on('committees')->nullOnDelete();

            // Note: school_id and primary_contact_person_id are plain references
            // (no FK constraint) to match the production schema, which predates
            // these migrations and was built without constraints on these columns.
            $table->unsignedInteger('school_id')->nullable();
            $table->string('town', 100)->nullable();
            $table->unsignedInteger('primary_contact_person_id')->nullable();

            $table->string('status', 30)->default('active');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('committees');
    }
};
