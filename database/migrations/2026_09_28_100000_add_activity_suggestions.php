<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

/**
 * Suggestions from registered parents on an activity that is still being
 * planned, starting with the Monster Celebration Party.
 *
 * Nothing a parent writes is shown until an admin approves it: the page is
 * public, and names are never shown on it. Peter's own opening ideas are
 * seeded already approved and carry no parent.
 */
return new class extends Migration
{
    private const PARTY_IDEAS = [
        'Venue: Midleton College',
        'Swimming pool',
        'Disco in the hall',
        'BBQ and/or pizza truck',
        'MC\'d by Red FM or similar',
        'Invited guests',
        'Tickets for Gen Alpha Rebels only',
    ];

    public function up(): void
    {
        Schema::table('activities', function (Blueprint $table) {
            $table->boolean('suggestions_open')->default(false)->after('link_label');
        });

        Schema::create('activity_suggestions', function (Blueprint $table) {
            $table->increments('id');

            $table->unsignedInteger('activity_id');
            $table->foreign('activity_id', 'fk_activity_suggestions_activity')
                ->references('id')->on('activities')->cascadeOnDelete();

            // Null for ideas entered by ECRC itself rather than by a parent.
            $table->unsignedInteger('parent_id')->nullable();
            $table->foreign('parent_id', 'fk_activity_suggestions_parent')
                ->references('id')->on('parents')->nullOnDelete();

            $table->string('body', 500);
            $table->enum('status', ['pending', 'approved', 'hidden'])->default('pending');
            $table->timestamp('approved_at')->nullable();
            $table->timestamps();

            $table->index(['activity_id', 'status'], 'ix_activity_suggestions_activity_status');
        });

        $now = now();

        $partyId = DB::table('activities')->insertGetId([
            'title' => 'Monster Celebration Party',
            'status' => 'planned',
            'venue_id' => null,
            'starts_on' => '2027-06-25',
            'schedule' => null,
            'summary' => 'An end-of-year celebration for the Gen Alpha Rebels. Plans are at an early stage '
                .'and suggestions are welcome.',
            'convenor_name' => "Peter O'Sullivan",
            'link_url' => null,
            'link_label' => null,
            'suggestions_open' => true,
            'sort_order' => 30,
            'is_active' => true,
            'created_at' => $now,
            'updated_at' => $now,
        ]);

        DB::table('activity_suggestions')->insert(array_map(fn (string $idea) => [
            'activity_id' => $partyId,
            'parent_id' => null,
            'body' => $idea,
            'status' => 'approved',
            'approved_at' => $now,
            'created_at' => $now,
            'updated_at' => $now,
        ], self::PARTY_IDEAS));
    }

    public function down(): void
    {
        Schema::dropIfExists('activity_suggestions');

        DB::table('activities')
            ->where('title', 'Monster Celebration Party')
            ->where('starts_on', '2027-06-25')
            ->delete();

        Schema::table('activities', function (Blueprint $table) {
            $table->dropColumn('suggestions_open');
        });
    }
};
