<?php

namespace App\Mail;

use App\Models\Parents;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class CoParentRemovedMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public Parents $inviter,
        public Parents $removed,
        public int $daysWaiting
    ) {
    }

    public function build(): static
    {
        return $this->subject('Your co-parent invite expired')
            ->markdown('mail.co-parent-removed');
    }
}
