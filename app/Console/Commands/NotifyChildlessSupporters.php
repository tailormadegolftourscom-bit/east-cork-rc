<?php

namespace App\Console\Commands;

use App\Mail\ChildlessSupporterReminderMail;
use App\Models\Supporter;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;

class NotifyChildlessSupporters extends Command
{
    protected $signature = 'app:notify-childless-supporters {--days=3 : How many days after joining before flagging}';

    protected $description = 'Email the admin oversight address about supporters who still have no children after N days';

    public function handle(): int
    {
        $days = (int) $this->option('days');

        $supporters = Supporter::query()
            ->where('is_active', true)
            ->whereNull('no_children_reminder_sent_at')
            ->where('created_at', '<=', now()->subDays($days))
            ->whereDoesntHave('person.children')
            ->with('person')
            ->get();

        foreach ($supporters as $supporter) {
            if (! $supporter->person) {
                continue;
            }

            $daysSinceJoined = (int) $supporter->created_at->diffInDays(now());

            Mail::to(config('mail.oversight_bcc'))
                ->send(new ChildlessSupporterReminderMail($supporter->person, $daysSinceJoined));

            $supporter->update(['no_children_reminder_sent_at' => now()]);
        }

        $this->info("Notified about {$supporters->count()} childless supporter(s).");

        return self::SUCCESS;
    }
}
