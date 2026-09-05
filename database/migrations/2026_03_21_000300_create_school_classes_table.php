<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('school_classes', function (Blueprint $table) {
            $table->increments('id');

            $table->unsignedInteger('school_id');
            $table->foreign('school_id')->references('id')->on('schools')->cascadeOnDelete();

            $table->enum('class_level', [
                'junior_infants', 'senior_infants',
                '1st_class', '2nd_class', '3rd_class', '4th_class', '5th_class', '6th_class',
                '1st_year', '2nd_year', '3rd_year', '4th_year', '5th_year', '6th_year',
            ]);
            $table->string('class_stream', 100)->nullable();
            $table->string('display_name', 50);
            $table->unsignedInteger('sort_order')->default(0);
            $table->unsignedInteger('total_pupils')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->unique(['school_id', 'class_level', 'class_stream'], 'uq_school_classes_school_level_stream');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('school_classes');
    }
};
