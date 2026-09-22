<?php

namespace App\Mail;

use App\Models\Parents;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ChildlessSupporterReminderMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public Parents $parent,
        public int $daysSinceJoined
    ) {
    }

    public function build(): static
    {
        return $this->subject('Still no child added: '.$this->parent->first_name.' '.$this->parent->last_name)
            ->markdown('mail.childless-supporter-reminder');
    }
}
