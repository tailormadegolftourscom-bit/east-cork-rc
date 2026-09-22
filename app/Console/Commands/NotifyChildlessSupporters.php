<?php

namespace App\Console\Commands;

use App\Mail\ChildlessSupporterReminderMail;
use App\Models\Parents;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;

class NotifyChildlessSupporters extends Command
{
    protected $signature = 'app:notify-childless-supporters {--days=3 : How many days after onboarding before flagging}';

    protected $description = 'Email the admin oversight address about parents who still have no children after N days';

    public function handle(): int
    {
        $days = (int) $this->option('days');

        // Works off parents directly now: with the opt-in row gone, having
        // finished onboarding is what "registered" means.
        $parents = Parents::query()
            ->where('user_type', 'parent')
            ->whereNotNull('onboarded_at')
            ->whereNull('no_children_reminder_sent_at')
            ->where('onboarded_at', '<=', now()->subDays($days))
            ->whereDoesntHave('children')
            // A co-parent owns no children but is connected to somebody
            // else's. Chasing them invites exactly the duplicate records this
            // is meant to avoid.
            ->whereDoesntHave('guardianOfChildren')
            ->get();

        foreach ($parents as $parent) {
            $daysSinceJoined = (int) $parent->onboarded_at->diffInDays(now());

            Mail::to(config('mail.oversight_bcc'))
                ->send(new ChildlessSupporterReminderMail($parent, $daysSinceJoined));

            $parent->forceFill(['no_children_reminder_sent_at' => now()])->save();
        }

        $this->info("Notified about {$parents->count()} childless parent(s).");

        return self::SUCCESS;
    }
}
