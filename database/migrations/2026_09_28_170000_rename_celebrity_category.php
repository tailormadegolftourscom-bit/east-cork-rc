<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

/**
 * "Celebrity" was an odd box for a member of the public to tick. It becomes
 * "Public figure", worded to the visitor. The slug stays, so existing
 * supporters keep their category. Only changed if still in its original
 * wording.
 */
return new class extends Migration
{
    public function up(): void
    {
        DB::table('supporter_categories')
            ->where('slug', 'celebrity')
            ->where('name', 'Celebrity')
            ->update([
                'name' => 'Public figure',
                'description' => "I'd like to lend my name, send a message to the kids, or come along to an event.",
                'updated_at' => now(),
            ]);
    }

    public function down(): void
    {
        DB::table('supporter_categories')
            ->where('slug', 'celebrity')
            ->where('name', 'Public figure')
            ->update([
                'name' => 'Celebrity',
                'description' => 'Endorsement, a message for the kids, personal appearances.',
            ]);
    }
};
