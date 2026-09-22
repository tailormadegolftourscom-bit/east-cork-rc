<?php

namespace App\Mail;

use App\Models\School;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class SchoolInviteMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public School $school
    ) {
    }

    public function build(): static
    {
        return $this->subject('East Cork Reclaim Childhood: school account for '.$this->school->name)
            ->markdown('mail.school-invite')
            ->bcc(config('mail.oversight_bcc'));
    }
}
