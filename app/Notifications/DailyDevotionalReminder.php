<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class DailyDevotionalReminder extends Notification
{
    use Queueable;

    /**
     * @param  array<string, string>  $reminder
     * @param  array<int, string>  $channels
     */
    public function __construct(public array $reminder, public array $channels = ['mail', 'database'])
    {
    }

    public function via(object $notifiable): array
    {
        return $this->channels;
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject($this->reminder['title'] ?? 'Your MannaRise path is ready')
            ->greeting('Hello '.$notifiable->name)
            ->line($this->reminder['message'] ?? 'It is time for your daily devotional rhythm.')
            ->line(($this->reminder['path_label'] ?? 'Personalized daily path').' · '.($this->reminder['reference'] ?? ''))
            ->action($this->reminder['action_label'] ?? 'Open MannaRise', $this->reminder['action_url'] ?? route('dashboard'))
            ->line('Keep growing one faithful step at a time.');
    }

    public function toArray(object $notifiable): array
    {
        return [
            'title' => $this->reminder['title'] ?? 'Your MannaRise path is ready',
            'message' => $this->reminder['message'] ?? 'It is time to read, pray, and reflect.',
            'url' => $this->reminder['action_url'] ?? route('dashboard'),
            'path_label' => $this->reminder['path_label'] ?? null,
            'reference' => $this->reminder['reference'] ?? null,
        ];
    }
}
