<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

/**
 * Venues double as the "local resources" on the Resources page — places in
 * East Cork where children can get out and do things — so each gets a
 * website link and a line saying what it is.
 *
 * Descriptions checked on 28 September 2026 against myplacemidleton.ie,
 * corkcoco.ie and the Ladysbridge Hall page. Only rows still without a
 * description are filled, so anything edited in admin is left alone.
 */
return new class extends Migration
{
    private const DETAILS = [
        'My Place Midleton' => [
            'Community centre on Mill Road, Midleton, for all ages: youth café, sports hall, study hub and arts space.',
            'https://www.myplacemidleton.ie/',
        ],
        'Midleton Community Centre' => [
            'Community and recreational centre on Bailick Road, Midleton, run by My Place Midleton.',
            'https://www.myplacemidleton.ie/',
        ],
        'Midleton–Youghal Greenway' => [
            '23km off-road walking and cycling route from Midleton to Youghal, via Mogeely and Killeagh.',
            'https://www.corkcoco.ie/en/resident/greenways/midleton-to-youghal-greenway',
        ],
        'Ladysbridge Community Hall' => [
            'The community hall in Ladysbridge village.',
            'https://www.facebook.com/ladysbridgehall/',
        ],
    ];

    public function up(): void
    {
        Schema::table('venues', function (Blueprint $table) {
            $table->string('website_url', 255)->nullable()->after('description');
        });

        foreach (self::DETAILS as $name => [$description, $url]) {
            DB::table('venues')
                ->where('name', $name)
                ->whereNull('description')
                ->update(['description' => $description, 'website_url' => $url, 'updated_at' => now()]);
        }
    }

    public function down(): void
    {
        Schema::table('venues', function (Blueprint $table) {
            $table->dropColumn('website_url');
        });
    }
};
