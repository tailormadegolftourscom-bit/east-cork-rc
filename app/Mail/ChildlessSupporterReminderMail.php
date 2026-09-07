<?php

namespace App\Mail;

use App\Models\Person;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ChildlessSupporterReminderMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public Person $person,
        public int $daysSinceJoined
    ) {
    }

    public function build(): static
    {
        return $this->subject('Still no child added: '.$this->person->first_name.' '.$this->person->last_name)
            ->markdown('mail.childless-supporter-reminder');
    }
}
