<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * RSVPs for the October workshops.
 *
 * The invitation asks people to RSVP "so that we have an idea of numbers", so
 * the numbers need somewhere to land rather than arriving as loose emails for
 * someone to tally by hand.
 *
 * Deliberately standalone: anyone can RSVP without registering, because the
 * workshops are open to parents who have not decided anything yet — asking
 * them to create an account first would lose exactly the people the evening
 * is meant to reach.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('workshop_rsvps', function (Blueprint $table) {
            $table->increments('id');

            $table->string('workshop', 30);
            $table->string('name', 150);
            $table->string('email', 150);
            $table->string('phone', 40)->nullable();

            $table->unsignedSmallInteger('adults')->default(1);
            $table->unsignedSmallInteger('children')->default(0);

            $table->text('note')->nullable();

            $table->timestamps();

            // One RSVP per person per evening; submitting again updates it.
            $table->unique(['workshop', 'email'], 'uq_workshop_rsvps');
            $table->index('workshop', 'ix_workshop_rsvps_workshop');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('workshop_rsvps');
    }
};
