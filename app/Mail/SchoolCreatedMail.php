<?php

namespace App\Mail;

use App\Models\School;
use App\Models\SchoolRegistrationRequest;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class SchoolCreatedMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public School $school,
        public SchoolRegistrationRequest $registrationRequest
    ) {
    }

    public function build(): static
    {
        return $this->subject('Your school has been added')
            ->markdown('mail.school-created')
            ->bcc(config('mail.oversight_bcc'));
    }
}
