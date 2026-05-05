<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\URL;

class WeeklySpiritualDigest extends Notification
{
    use Queueable;

    /**
     * @param  array<string, mixed>  $digest
     * @param  array<int, string>  $channels
     */
    public function __construct(public array $digest, public array $channels = ['mail', 'database'])
    {
    }

    public function via(object $notifiable): array
    {
        return $this->channels;
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Your MannaRise weekly spiritual digest')
            ->greeting('Hello '.$notifiable->name)
            ->line($this->digest['sentence'])
            ->line('This week is not about perfection. It is about noticing where grace is forming consistency.')
            ->action('Open your dashboard', route('dashboard'))
            ->line('You can turn off email notifications here: '.URL::temporarySignedRoute('mail.notifications.opt-out', now()->addDays(30), ['user' => $notifiable->id]));
    }

    public function toArray(object $notifiable): array
    {
        return [
            'title' => 'Weekly spiritual digest',
            'message' => $this->digest['sentence'],
            'url' => route('dashboard'),
            'digest' => $this->digest,
        ];
    }
}
