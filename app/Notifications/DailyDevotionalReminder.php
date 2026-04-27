<?php

namespace App\Notifications;

use App\Models\Devotional;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class DailyDevotionalReminder extends Notification
{
    use Queueable;

    public function __construct(public ?Devotional $devotional = null)
    {
    }

    public function via(object $notifiable): array
    {
        return ['mail', 'database'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        $message = (new MailMessage)
            ->subject('Your MannaRise devotional reminder')
            ->greeting('Hello '.$notifiable->name)
            ->line('It is time for your daily devotional rhythm.');

        if ($this->devotional) {
            $message
                ->line($this->devotional->title)
                ->action('Read devotional', route('devotionals.show', $this->devotional->slug));
        } else {
            $message->action('Open MannaRise', route('devotionals.index'));
        }

        return $message->line('Keep growing one faithful step at a time.');
    }

    public function toArray(object $notifiable): array
    {
        return [
            'title' => 'Daily devotional reminder',
            'message' => $this->devotional ? $this->devotional->title : 'It is time to read and reflect.',
            'devotional_id' => $this->devotional?->id,
            'url' => $this->devotional ? route('devotionals.show', $this->devotional->slug) : route('devotionals.index'),
        ];
    }
}
