<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\Lang;

/**
 * "Set your password" rather than "reset" it.
 *
 * Almost every link this app sends goes to someone who has never had a
 * password: an invited school principal, a co-parent, a half-finished
 * registration. Laravel's stock notification tells them to reset something
 * they never had, which reads like a security alert for an account they don't
 * recognise — exactly the wrong first impression for a cold invite.
 */
class SetPasswordNotification extends Notification
{
    use Queueable;

    public function __construct(
        public string $token
    ) {
    }

    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        $minutes = config('auth.passwords.users.expire', 1440);
        $hours = (int) round($minutes / 60);

        return (new MailMessage)
            ->subject('Set your password for East Cork Reclaim Childhood')
            ->greeting('Hello'.($notifiable->first_name ? ' '.$notifiable->first_name : '').',')
            ->line('Use the button below to choose a password. That is all that is needed to finish setting up your account.')
            ->action('Set My Password', $this->resetUrl($notifiable))
            ->line(Lang::get('This link lasts :count hours. If it has expired by the time you get to it, there is a button on the page to send a fresh one.', ['count' => $hours]))
            ->line('If you were not expecting this, you can ignore it and nothing will happen.')
            ->salutation('East Cork Reclaim Childhood');
    }

    private function resetUrl(object $notifiable): string
    {
        return url(route('password.reset', [
            'token' => $this->token,
            'email' => $notifiable->getEmailForPasswordReset(),
        ], false));
    }
}
