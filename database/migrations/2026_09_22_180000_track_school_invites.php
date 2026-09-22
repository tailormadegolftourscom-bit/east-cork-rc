<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

/**
 * Records when a school invite went out, and when the school answered.
 *
 * Writing to a principal cold is a one-way step, and until now the button
 * that does it looked like every other button and could be pressed again with
 * no trace. Nothing recorded that an invite had been sent at all — it could
 * only be inferred from a school account existing, or from an unused password
 * reset token lying around.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('schools', function (Blueprint $table) {
            $table->timestamp('invite_sent_at')->nullable()->after('classes_confirmed');

            // Set by an admin when the school replies by any means. A school
            // that sets its own password is detected from its account instead
            // and needs no button.
            $table->timestamp('invite_response_at')->nullable()->after('invite_sent_at');
            $table->string('invite_response_note', 255)->nullable()->after('invite_response_at');
        });

        $this->backfill();
    }

    /**
     * Two invites have already gone out. Reconstruct their dates rather than
     * leaving them blank, so the "days since" figure is right from day one.
     */
    private function backfill(): void
    {
        // A school account only ever comes into being by sending an invite or
        // approving a school request, so its creation date is the send date.
        foreach (DB::table('parents')->where('user_type', 'school')->whereNotNull('school_id')->get() as $account) {
            DB::table('schools')
                ->where('id', $account->school_id)
                ->whereNull('invite_sent_at')
                ->update(['invite_sent_at' => $account->created_at]);
        }

        // An unused reset token addressed to a principal is the fingerprint of
        // an invite whose account has since been deleted — which is exactly
        // the case for the one real school invited so far.
        if (! Schema::hasTable('password_reset_tokens')) {
            return;
        }

        foreach (DB::table('password_reset_tokens')->get() as $token) {
            DB::table('schools')
                ->whereRaw('LOWER(principal_email) = ?', [mb_strtolower($token->email)])
                ->whereNull('invite_sent_at')
                ->update(['invite_sent_at' => $token->created_at]);
        }
    }

    public function down(): void
    {
        Schema::table('schools', function (Blueprint $table) {
            $table->dropColumn(['invite_sent_at', 'invite_response_at', 'invite_response_note']);
        });
    }
};
