<?php

namespace App\Mail;

use App\Models\Person;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class NewSupporterMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public Person $person
    ) {
    }

    public function build(): static
    {
        return $this->subject('New parent registered: '.$this->person->first_name.' '.$this->person->last_name)
            ->markdown('mail.new-supporter');
    }
}
