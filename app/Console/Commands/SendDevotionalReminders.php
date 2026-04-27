<?php

namespace App\Console\Commands;

use App\Models\Devotional;
use App\Models\DevotionalReminder;
use App\Notifications\DailyDevotionalReminder;
use Illuminate\Console\Command;

class SendDevotionalReminders extends Command
{
    protected $signature = 'mannarise:send-devotional-reminders';

    protected $description = 'Send due devotional reminder notifications.';

    public function handle(): int
    {
        $devotional = Devotional::published()->latest('published_at')->first();
        $sent = 0;

        DevotionalReminder::with('user')
            ->where('is_active', true)
            ->where('email_enabled', true)
            ->whereTime('remind_at', '<=', now()->format('H:i:s'))
            ->where(function ($query) {
                $query->whereNull('last_sent_at')->orWhereDate('last_sent_at', '<', today());
            })
            ->chunkById(100, function ($reminders) use ($devotional, &$sent): void {
                foreach ($reminders as $reminder) {
                    if ($reminder->user) {
                        $reminder->user->notify(new DailyDevotionalReminder($devotional));
                        $reminder->forceFill(['last_sent_at' => now()])->save();
                        $sent++;
                    }
                }
            });

        $this->components->info('Sent '.$sent.' devotional reminder(s).');

        return self::SUCCESS;
    }
}
