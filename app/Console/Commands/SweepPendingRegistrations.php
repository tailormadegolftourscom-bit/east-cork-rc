<?php

namespace App\Console\Commands;

use App\Mail\CoParentRemovedMail;
use App\Mail\PendingRegistrationReminderMail;
use App\Models\AdminAuditLog;
use App\Models\Parents;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Password;

/**
 * Chases registrations that were started but never finished, then clears them
 * out: a reminder at 3 days, another at 6, removal at 9.
 *
 * "Not finished" means no password of their own — registration_completed_at
 * is null. Email verification cannot stand in for it, because co-parent
 * invites arrive already verified, so an invite nobody ever answered still
 * looks verified.
 */
class SweepPendingRegistrations extends Command
{
    protected $signature = 'app:sweep-pending-registrations
                            {--first=3 : Days before the first reminder}
                            {--second=6 : Days before the second reminder}
                            {--remove=9 : Days before the account is removed}
                            {--dry-run : Report what would happen without sending or deleting}';

    protected $description = 'Remind unfinished parent registrations at 3 and 6 days, remove them at 9';

    public function handle(): int
    {
        $first = (int) $this->option('first');
        $second = (int) $this->option('second');
        $remove = (int) $this->option('remove');
        $dry = (bool) $this->option('dry-run');

        $pending = Parents::query()
            ->where('user_type', 'parent')
            ->whereNull('registration_completed_at')
            // A row with no password hash was never an invited account, just
            // a contact record. Chasing it would mail someone who never
            // signed up and hand them a reset link for an account they never
            // asked for.
            ->whereNotNull('password')
            ->with('guardianOfChildren.owner')
            ->get();

        $reminded = 0;
        $removed = 0;

        foreach ($pending as $parent) {
            $age = (int) ($parent->invited_at ?? $parent->created_at)->diffInDays(now());

            if ($age >= $remove) {
                $removed += (int) $this->remove($parent, $age, $dry);

                continue;
            }

            $due = match (true) {
                $age >= $second && $parent->pending_reminder_count < 2 => 2,
                $age >= $first && $parent->pending_reminder_count < 1 => 1,
                default => null,
            };

            if ($due === null) {
                continue;
            }

            $reminded += (int) $this->remind($parent, $due, $age, $dry);
        }

        $this->info(($dry ? '[dry run] ' : '')."Reminded {$reminded}, removed {$removed}, {$pending->count()} pending in total.");

        return self::SUCCESS;
    }

    private function remind(Parents $parent, int $which, int $age, bool $dry): bool
    {
        $this->line(($dry ? '[dry run] ' : '')."Reminder {$which} -> {$parent->email} ({$age} days)");

        if ($dry) {
            return true;
        }

        // A fresh reset link each time: the original may well have expired by
        // now, and a reminder pointing at a dead link is worse than none.
        Mail::to($parent->email)->send(new PendingRegistrationReminderMail($parent, $which, $age));
        Password::sendResetLink(['email' => $parent->email]);

        $parent->forceFill([
            'pending_reminder_count' => $which,
            'pending_reminder_sent_at' => now(),
        ])->save();

        return true;
    }

    private function remove(Parents $parent, int $age, bool $dry): bool
    {
        // Someone who cannot log in cannot have registered a child, but check
        // rather than assume — deleting would cascade and take them along.
        if ($parent->children()->exists()) {
            $this->warn("Skipping {$parent->email}: owns children despite never completing registration.");

            return false;
        }

        $this->line(($dry ? '[dry run] ' : '')."Removing {$parent->email} ({$age} days, never finished)");

        if ($dry) {
            return true;
        }

        // Tell whoever invited them, so the co-parent does not simply vanish
        // from the inviter's account with no explanation.
        $inviters = $parent->guardianOfChildren
            ->map(fn ($child) => $child->owner)
            ->filter()
            ->unique('id');

        foreach ($inviters as $inviter) {
            Mail::to($inviter->email)->send(new CoParentRemovedMail($inviter, $parent, $age));
        }

        AdminAuditLog::create([
            'actor_id' => null,
            'action' => 'parent.purge_unfinished',
            'target_type' => Parents::class,
            'target_id' => $parent->id,
            'reason' => "Registration never completed after {$age} days.",
            'context' => ['email' => $parent->email, 'invited_by' => $inviters->pluck('email')->all()],
        ]);

        $parent->delete();

        return true;
    }
}
