<?php

namespace App\Support;

use App\Models\DailyRhythmCheckIn;
use App\Models\User;
use Carbon\CarbonImmutable;

class SpiritualGrowthScore
{
    public static function forUser(User $user): array
    {
        $weekly = self::window($user, 7);
        $monthly = self::window($user, 30);

        return [
            'score' => $weekly['score'],
            'monthly_score' => $monthly['score'],
            'trend' => $weekly['score'] - $monthly['score'],
            'label' => self::label($weekly['score']),
            'breakdown' => $weekly['breakdown'],
            'weekly' => $weekly,
            'monthly' => $monthly,
        ];
    }

    private static function window(User $user, int $days): array
    {
        $start = CarbonImmutable::today()->subDays($days - 1);
        $dailyRows = DailyRhythmCheckIn::query()
            ->where('user_id', $user->id)
            ->whereDate('checked_on', '>=', $start)
            ->get();

        $devotionalDays = $user->devotionalCompletions()
            ->whereDate('completed_on', '>=', $start)
            ->distinct('completed_on')
            ->count('completed_on');
        $journalDays = $user->journalEntries()
            ->whereDate('entry_date', '>=', $start)
            ->distinct('entry_date')
            ->count('entry_date');
        $prayerCount = $user->prayerRequests()
            ->where('created_at', '>=', $start)
            ->count();
        $testimonyCount = $user->testimonies()
            ->where('created_at', '>=', $start)
            ->count();
        $bibleChapters = $user->bibleChapterCompletions()
            ->where('completed_at', '>=', $start)
            ->count();

        $verseDays = $dailyRows->whereNotNull('verse_completed_at')->count();
        $affirmationDays = $dailyRows->whereNotNull('affirmation_completed_at')->count();
        $challengeDays = $dailyRows->whereNotNull('challenge_completed_at')->count();

        $breakdown = [
            'Daily rhythm' => self::percent($verseDays + $affirmationDays + $challengeDays, $days * 3),
            'Devotionals' => self::percent($devotionalDays, $days),
            'Bible reading' => max(self::percent($challengeDays, $days), self::percent($bibleChapters, $days * 3)),
            'Reflection' => self::percent($journalDays, max(1, (int) ceil($days / 2))),
            'Prayer' => self::percent($prayerCount, max(1, (int) ceil($days / 4))),
            'Witness' => self::percent($testimonyCount, max(1, (int) ceil($days / 14))),
        ];

        $score = (int) round(
            $breakdown['Daily rhythm'] * 0.25
            + $breakdown['Devotionals'] * 0.25
            + $breakdown['Bible reading'] * 0.20
            + $breakdown['Reflection'] * 0.15
            + $breakdown['Prayer'] * 0.10
            + $breakdown['Witness'] * 0.05
        );

        return [
            'days' => $days,
            'score' => min(100, $score),
            'breakdown' => $breakdown,
            'counts' => [
                'devotional_days' => $devotionalDays,
                'journal_days' => $journalDays,
                'prayers' => $prayerCount,
                'testimonies' => $testimonyCount,
                'bible_chapters' => $bibleChapters,
                'daily_check_ins' => $dailyRows->count(),
            ],
        ];
    }

    private static function percent(int $actual, int $target): int
    {
        return $target > 0 ? min(100, (int) round(($actual / $target) * 100)) : 0;
    }

    private static function label(int $score): string
    {
        return match (true) {
            $score >= 85 => 'Flourishing',
            $score >= 65 => 'Steady',
            $score >= 40 => 'Growing',
            default => 'Restart gently',
        };
    }
}
