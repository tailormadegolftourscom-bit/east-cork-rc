<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * When a parent's last child record ended, so the retention job can remove
 * the account 12 months later as the privacy notice promises. Set whenever a
 * child the parent owns is deleted.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('parents', function (Blueprint $table) {
            $table->timestamp('last_child_ended_at')->nullable()->after('onboarded_at');
        });
    }

    public function down(): void
    {
        Schema::table('parents', function (Blueprint $table) {
            $table->dropColumn('last_child_ended_at');
        });
    }
};
