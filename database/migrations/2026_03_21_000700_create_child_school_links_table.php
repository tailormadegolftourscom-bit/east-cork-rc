<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('child_school_links', function (Blueprint $table) {
            $table->increments('id');

            $table->unsignedInteger('child_id')->unique('uq_child_school_links_child');
            $table->foreign('child_id', 'fk_child_school_links_child')
                ->references('id')->on('children')->cascadeOnDelete();

            $table->unsignedInteger('current_school_id');
            $table->foreign('current_school_id', 'fk_child_school_links_current_school')
                ->references('id')->on('schools')->cascadeOnDelete();

            $table->unsignedInteger('current_school_class_id');
            $table->foreign('current_school_class_id', 'fk_child_school_links_current_class')
                ->references('id')->on('school_classes')->cascadeOnDelete();

            $table->unsignedInteger('likely_secondary_school_id')->nullable();
            $table->foreign('likely_secondary_school_id', 'fk_child_school_links_likely_secondary')
                ->references('id')->on('schools')->nullOnDelete();

            $table->enum('transition_status', ['not_applicable', 'considering', 'likely', 'confirmed'])
                ->default('not_applicable');

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('child_school_links');
    }
};
