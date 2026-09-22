<?php

namespace App\Mail;

use App\Models\WorkshopRsvp;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class WorkshopRsvpMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public WorkshopRsvp $rsvp
    ) {
    }

    public function build(): static
    {
        return $this->subject('You are down for the '.$this->rsvp->label().' workshop')
            ->markdown('mail.workshop-rsvp')
            ->bcc(config('mail.oversight_bcc'));
    }
}
