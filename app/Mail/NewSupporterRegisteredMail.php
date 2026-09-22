<?php

namespace App\Mail;

use App\Models\Supporter;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class NewSupporterRegisteredMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public Supporter $supporter
    ) {
    }

    public function build(): static
    {
        return $this->subject('New supporter: '.$this->supporter->full_name)
            ->markdown('mail.new-supporter-registered');
    }
}
