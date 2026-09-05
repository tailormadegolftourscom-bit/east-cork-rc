<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('supporters', function (Blueprint $table) {
            $table->increments('id');

            $table->unsignedInteger('person_id')->unique();
            $table->foreign('person_id')->references('id')->on('people')->cascadeOnDelete();

            $table->enum('support_status', ['supporting', 'undecided'])->default('supporting');
            $table->boolean('is_active')->default(true);
            $table->timestamp('joined_at')->useCurrent();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('supporters');
    }
};
