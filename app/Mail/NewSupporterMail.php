<?php

namespace App\Mail;

use App\Models\Parents;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class NewSupporterMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public Parents $parent
    ) {
    }

    public function build(): static
    {
        return $this->subject('New parent registered: '.$this->parent->first_name.' '.$this->parent->last_name)
            ->markdown('mail.new-supporter');
    }
}
