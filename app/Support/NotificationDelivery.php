<?php

namespace App\Support;

use App\Models\DevotionalReminder;
use App\Models\NotificationDeliveryLog;
use App\Notifications\DailyDevotionalReminder;
use App\Notifications\WeeklySpiritualDigest;
use Carbon\CarbonImmutable;
use Throwable;

class NotificationDelivery
{
    public static function dailyDue(DevotionalReminder $reminder): bool
    {
        $now = self::nowFor($reminder);
        $weekdays = $reminder->days['weekdays'] ?? [];

        if (! $reminder->is_active || ! $reminder->user) {
            return false;
        }

        if ($weekdays && ! in_array(strtolower($now->englishDayOfWeek), $weekdays, true)) {
            return false;
        }

        if ($reminder->last_sent_at && $reminder->last_sent_at->copy()->timezone($now->getTimezone())->isSameDay($now)) {
            return false;
        }

        return substr((string) $reminder->remind_at, 0, 5) <= $now->format('H:i');
    }

    public static function weeklyDue(DevotionalReminder $reminder): bool
    {
        $now = self::nowFor($reminder);

        if (! $reminder->is_active || ! $reminder->user || ! in_array('digest', $reminder->days['types'] ?? [], true)) {
            return false;
        }

        if ($now->englishDayOfWeek !== 'Sunday') {
            return false;
        }

        if (substr((string) $reminder->remind_at, 0, 5) > $now->format('H:i')) {
            return false;
        }

        return ! NotificationDeliveryLog::where('user_id', $reminder->user_id)
            ->where('notification_type', 'weekly_digest')
            ->where('status', 'sent')
            ->where('sent_at', '>=', $now->startOfDay()->timezone(config('app.timezone')))
            ->exists();
    }

    public static function nextDailySendAt(DevotionalReminder $reminder): ?CarbonImmutable
    {
        return SpiritualRetentionSummary::nextReminderAt($reminder);
    }

    /**
     * @return array{sent: bool, message: string}
     */
    public static function sendDaily(DevotionalReminder $reminder, bool $mailOnly = false): array
    {
        if (! $reminder->user) {
            return ['sent' => false, 'message' => 'Reminder has no user.'];
        }

        $channels = self::channelsFor($reminder, $mailOnly);

        if ($channels === []) {
            return ['sent' => false, 'message' => 'User has opted out of mail notifications.'];
        }

        $summary = SpiritualRetentionSummary::dailyReminder($reminder->user, $reminder->days['types'] ?? []);

        try {
            $reminder->user->notify(new DailyDevotionalReminder($summary, $channels));
            $reminder->forceFill(['last_sent_at' => now()])->save();
            self::log($reminder, $channels, 'daily_reminder', 'sent', $summary['title'] ?? 'Your MannaRise path is ready', $summary['message'] ?? null, $summary['action_url'] ?? null, [
                'reminder_id' => $reminder->id,
                'types' => $reminder->days['types'] ?? [],
                'manual' => $mailOnly,
            ]);

            return ['sent' => true, 'message' => 'Daily reminder sent.'];
        } catch (Throwable $exception) {
            self::log($reminder, $channels, 'daily_reminder', 'failed', $summary['title'] ?? 'Your MannaRise path is ready', $summary['message'] ?? null, $summary['action_url'] ?? null, [
                'reminder_id' => $reminder->id,
                'types' => $reminder->days['types'] ?? [],
                'manual' => $mailOnly,
            ], $exception->getMessage());

            return ['sent' => false, 'message' => $exception->getMessage()];
        }
    }

    /**
     * @return array{sent: bool, message: string}
     */
    public static function sendWeeklyDigest(DevotionalReminder $reminder, bool $mailOnly = false): array
    {
        if (! $reminder->user) {
            return ['sent' => false, 'message' => 'Reminder has no user.'];
        }

        if (! in_array('digest', $reminder->days['types'] ?? [], true)) {
            return ['sent' => false, 'message' => 'User has not enabled weekly digest.'];
        }

        $channels = self::channelsFor($reminder, $mailOnly);

        if ($channels === []) {
            return ['sent' => false, 'message' => 'User has opted out of mail notifications.'];
        }

        $digest = SpiritualRetentionSummary::weeklyDigest($reminder->user);

        try {
            $reminder->user->notify(new WeeklySpiritualDigest($digest, $channels));
            self::log($reminder, $channels, 'weekly_digest', 'sent', 'Your MannaRise weekly spiritual digest', $digest['sentence'] ?? null, route('dashboard'), $digest + ['manual' => $mailOnly]);

            return ['sent' => true, 'message' => 'Weekly digest sent.'];
        } catch (Throwable $exception) {
            self::log($reminder, $channels, 'weekly_digest', 'failed', 'Your MannaRise weekly spiritual digest', $digest['sentence'] ?? null, route('dashboard'), $digest + ['manual' => $mailOnly], $exception->getMessage());

            return ['sent' => false, 'message' => $exception->getMessage()];
        }
    }

    /**
     * @return array<int, string>
     */
    public static function channelsFor(DevotionalReminder $reminder, bool $mailOnly = false): array
    {
        if ($mailOnly) {
            return $reminder->email_enabled ? ['mail'] : [];
        }

        return collect([
            $reminder->email_enabled ? 'mail' : null,
            $reminder->push_enabled ? 'database' : null,
        ])->filter()->values()->all();
    }

    private static function nowFor(DevotionalReminder $reminder): CarbonImmutable
    {
        try {
            return CarbonImmutable::now($reminder->timezone ?: config('app.timezone'));
        } catch (Throwable) {
            return CarbonImmutable::now();
        }
    }

    /**
     * @param  array<int, string>  $channels
     * @param  array<string, mixed>  $meta
     */
    private static function log(DevotionalReminder $reminder, array $channels, string $type, string $status, ?string $subject, ?string $message, ?string $actionUrl, array $meta = [], ?string $error = null): void
    {
        foreach ($channels as $channel) {
            NotificationDeliveryLog::create([
                'user_id' => $reminder->user_id,
                'notification_type' => $type,
                'channel' => $channel,
                'status' => $status,
                'subject' => $subject,
                'message' => $message,
                'action_url' => $actionUrl,
                'error_message' => $error,
                'meta' => $meta,
                'sent_at' => $status === 'sent' ? now() : null,
            ]);
        }
    }
}
