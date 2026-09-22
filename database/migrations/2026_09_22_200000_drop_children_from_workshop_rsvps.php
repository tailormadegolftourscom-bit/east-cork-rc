<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

/**
 * The workshops are evening discussions for parents, so asking how many
 * children are coming invited the wrong expectation — and with children gone
 * "adults" is just the number of people, which `attendees` says plainly.
 *
 * Safe to drop: the only two rows are test RSVPs with children = 0.
 */
return new class extends Migration
{
    public function up(): void
    {
        $mysql = DB::getDriverName() === 'mysql';

        // Fold any children recorded so far into the head count rather than
        // discarding them, in case one slipped in between deploys.
        if (Schema::hasColumn('workshop_rsvps', 'children')) {
            DB::table('workshop_rsvps')
                ->where('children', '>', 0)
                ->update(['adults' => DB::raw('adults + children')]);
        }

        Schema::table('workshop_rsvps', function (Blueprint $table) {
            $table->dropColumn('children');
        });

        $mysql
            ? DB::statement('ALTER TABLE `workshop_rsvps` CHANGE `adults` `attendees` SMALLINT UNSIGNED NOT NULL DEFAULT 1')
            : Schema::table('workshop_rsvps', fn (Blueprint $t) => $t->renameColumn('adults', 'attendees'));
    }

    public function down(): void
    {
        $mysql = DB::getDriverName() === 'mysql';

        $mysql
            ? DB::statement('ALTER TABLE `workshop_rsvps` CHANGE `attendees` `adults` SMALLINT UNSIGNED NOT NULL DEFAULT 1')
            : Schema::table('workshop_rsvps', fn (Blueprint $t) => $t->renameColumn('attendees', 'adults'));

        Schema::table('workshop_rsvps', function (Blueprint $table) {
            $table->unsignedSmallInteger('children')->default(0)->after('adults');
        });
    }
};
