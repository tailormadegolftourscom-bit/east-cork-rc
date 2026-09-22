<?php

namespace App\Mail;

use App\Models\Parents;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class PendingRegistrationReminderMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public Parents $parent,
        public int $reminderNumber,
        public int $daysWaiting
    ) {
    }

    public function build(): static
    {
        return $this->subject('Finish setting up your East Cork Reclaim Childhood account')
            ->markdown('mail.pending-registration-reminder');
    }
}
