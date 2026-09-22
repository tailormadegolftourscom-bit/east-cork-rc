<?php

namespace App\Mail;

use App\Models\Committee;
use Illuminate\Bus\Queueable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class AddedToCommitteeMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public Model $person,
        public Committee $committee
    ) {
    }

    public function build(): static
    {
        return $this->subject('You have been added to '.$this->committee->name)
            ->markdown('mail.added-to-committee');
    }
}
