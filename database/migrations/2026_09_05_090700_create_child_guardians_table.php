<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('child_guardians', function (Blueprint $table) {
            $table->increments('id');

            $table->unsignedInteger('child_id');
            $table->foreign('child_id', 'fk_child_guardians_child')
                ->references('id')->on('children')->cascadeOnDelete();

            $table->unsignedInteger('person_id');
            $table->foreign('person_id', 'fk_child_guardians_person')
                ->references('id')->on('people')->cascadeOnDelete();

            $table->timestamps();

            $table->unique(['child_id', 'person_id'], 'uq_child_guardians_child_person');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('child_guardians');
    }
};
