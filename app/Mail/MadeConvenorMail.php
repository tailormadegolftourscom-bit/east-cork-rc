<?php

namespace App\Mail;

use App\Models\Committee;
use Illuminate\Bus\Queueable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class MadeConvenorMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public Model $person,
        public Committee $committee
    ) {
    }

    public function build(): static
    {
        return $this->subject('You are now convenor of '.$this->committee->name)
            ->markdown('mail.made-convenor');
    }
}
