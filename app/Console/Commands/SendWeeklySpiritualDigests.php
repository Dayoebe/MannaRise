<?php

namespace App\Console\Commands;

use App\Models\DevotionalReminder;
use App\Support\NotificationDelivery;
use Illuminate\Console\Command;

class SendWeeklySpiritualDigests extends Command
{
    protected $signature = 'mannarise:send-weekly-spiritual-digests';

    protected $description = 'Send weekly spiritual progress digest notifications.';

    public function handle(): int
    {
        $sent = 0;

        DevotionalReminder::with('user')
            ->where('is_active', true)
            ->where(function ($query) {
                $query->where('email_enabled', true)->orWhere('push_enabled', true);
            })
            ->chunkById(100, function ($reminders) use (&$sent): void {
                foreach ($reminders as $reminder) {
                    if (! NotificationDelivery::weeklyDue($reminder)) {
                        continue;
                    }

                    if (NotificationDelivery::sendWeeklyDigest($reminder)['sent']) {
                        $sent++;
                    }
                }
            });

        $this->components->info('Sent '.$sent.' weekly spiritual digest(s).');

        return self::SUCCESS;
    }
}
