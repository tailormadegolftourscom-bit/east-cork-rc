<?php

namespace App\Mail;

use App\Models\Person;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class CoParentInviteMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public Person $inviter,
        public bool $isNewAccount
    ) {
    }

    public function build(): static
    {
        return $this->subject('You\'ve been added as a co-parent on East Cork Reclaim Childhood')
            ->markdown('mail.co-parent-invite')
            ->bcc(config('mail.oversight_bcc'));
    }
}
