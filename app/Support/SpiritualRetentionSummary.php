<?php

namespace App\Support;

use App\Models\DevotionalReminder;
use App\Models\PersonalizedDailyPathCheckIn;
use App\Models\User;
use Carbon\CarbonImmutable;
use Illuminate\Support\Collection;
use Throwable;

class SpiritualRetentionSummary
{
    /**
     * @return array<string, mixed>
     */
    public static function weeklyDigest(User $user, ?CarbonImmutable $today = null): array
    {
        $today ??= CarbonImmutable::today();
        $start = $today->subDays(6)->startOfDay();
        $end = $today->endOfDay();

        $devotionalDays = $user->devotionalCompletions()
            ->whereBetween('completed_on', [$start->toDateString(), $end->toDateString()])
            ->pluck('completed_on')
            ->map(fn ($date) => CarbonImmutable::parse($date)->toDateString())
            ->unique()
            ->values();

        $bibleDates = $user->bibleReadingHistories()
            ->whereBetween('last_read_at', [$start, $end])
            ->pluck('last_read_at')
            ->merge($user->bibleChapterCompletions()->whereBetween('completed_at', [$start, $end])->pluck('completed_at'))
            ->map(fn ($date) => CarbonImmutable::parse($date)->toDateString())
            ->unique()
            ->values();

        $prayerDates = $user->prayerRequests()
            ->whereBetween('created_at', [$start, $end])
            ->pluck('created_at')
            ->merge($user->prayerRoomPrayers()->whereBetween('prayed_on', [$start->toDateString(), $end->toDateString()])->pluck('prayed_on'))
            ->merge(PersonalizedDailyPathCheckIn::where('user_id', $user->id)->whereBetween('checked_on', [$start->toDateString(), $end->toDateString()])->whereNotNull('prayer_completed_at')->pluck('checked_on'))
            ->map(fn ($date) => CarbonImmutable::parse($date)->toDateString())
            ->unique()
            ->values();

        $journalEntries = $user->journalEntries()
            ->whereBetween('entry_date', [$start->toDateString(), $end->toDateString()])
            ->get();

        $journalTopics = $journalEntries
            ->flatMap(fn ($entry) => collect($entry->topics ?? []))
            ->map(fn ($topic) => strtolower(trim((string) $topic)))
            ->filter()
            ->countBy()
            ->sortDesc();

        $activeDates = $devotionalDays
            ->merge($bibleDates)
            ->merge($prayerDates)
            ->merge($journalEntries->pluck('entry_date')->map(fn ($date) => CarbonImmutable::parse($date)->toDateString()))
            ->unique()
            ->values();

        $consistentDays = self::longestRecentRun($activeDates, $today);
        $topTopic = $journalTopics->keys()->first();
        $topTopicCount = $topTopic ? (int) $journalTopics->first() : 0;

        $sentence = self::digestSentence(
            prayerDays: $prayerDates->count(),
            devotionalDays: $devotionalDays->count(),
            bibleDays: $bibleDates->count(),
            journalCount: $journalEntries->count(),
            topTopic: $topTopic,
            topTopicCount: $topTopicCount,
            consistentDays: $consistentDays,
        );

        return [
            'start' => $start,
            'end' => $end,
            'prayer_days' => $prayerDates->count(),
            'devotional_days' => $devotionalDays->count(),
            'bible_days' => $bibleDates->count(),
            'journal_entries' => $journalEntries->count(),
            'top_journal_topic' => $topTopic,
            'top_journal_topic_count' => $topTopicCount,
            'consistent_days' => $consistentDays,
            'active_days' => $activeDates->count(),
            'sentence' => $sentence,
        ];
    }

    /**
     * @param  array<int, string>  $types
     * @return array<string, string>
     */
    public static function dailyReminder(User $user, array $types = []): array
    {
        $profile = $user->spiritualProfile()->first();
        $path = PersonalizedDailyPath::forSeason($profile?->season);
        $definition = $path['definition'];
        $devotional = $path['devotional'];
        $nudge = self::missedDayNudge($user, $types);

        return [
            'title' => $nudge['title'] ?? 'Your MannaRise path is ready',
            'message' => $nudge['message'] ?? "Today's path: {$definition['label']} with {$definition['reference']}.",
            'action_label' => $devotional ? 'Open today\'s path devotional' : 'Open today\'s path',
            'action_url' => $devotional ? route('devotionals.show', $devotional->slug) : route('growth-path.index'),
            'path_label' => $definition['label'],
            'reference' => $definition['reference'],
        ];
    }

    /**
     * @param  array<int, string>  $types
     * @return array<string, string>|null
     */
    public static function missedDayNudge(User $user, array $types = []): ?array
    {
        if ($types && ! in_array('missed', $types, true)) {
            return null;
        }

        $yesterday = CarbonImmutable::yesterday();
        $devotionalDone = $user->devotionalCompletions()->whereDate('completed_on', $yesterday->toDateString())->exists();
        $bibleDone = $user->bibleReadingHistories()->whereDate('last_read_at', $yesterday->toDateString())->exists()
            || $user->bibleChapterCompletions()->whereDate('completed_at', $yesterday->toDateString())->exists();
        $prayerDone = $user->prayerRequests()->whereDate('created_at', $yesterday->toDateString())->exists()
            || $user->prayerRoomPrayers()->whereDate('prayed_on', $yesterday->toDateString())->exists()
            || PersonalizedDailyPathCheckIn::where('user_id', $user->id)->whereDate('checked_on', $yesterday->toDateString())->whereNotNull('prayer_completed_at')->exists();

        $missed = collect([
            'Bible' => ! $bibleDone,
            'prayer' => ! $prayerDone,
            'devotional' => ! $devotionalDone,
        ])->filter();

        if ($missed->count() < 2) {
            return null;
        }

        return [
            'title' => 'A gentle reset for today',
            'message' => 'Yesterday was lighter in '.str($missed->keys()->join(', '))->lower().'. Start again with today\'s personalized path.',
        ];
    }

    public static function nextReminderAt(?DevotionalReminder $reminder): ?CarbonImmutable
    {
        if (! $reminder || ! $reminder->is_active) {
            return null;
        }

        try {
            $timezone = $reminder->timezone ?: config('app.timezone');
            $now = CarbonImmutable::now($timezone);
            [$hour, $minute] = array_map('intval', explode(':', substr((string) $reminder->remind_at, 0, 5)));
            $weekdays = $reminder->days['weekdays'] ?? [];

            for ($offset = 0; $offset <= 14; $offset++) {
                $candidate = $now->addDays($offset)->setTime($hour, $minute);

                if ($weekdays && ! in_array(strtolower($candidate->englishDayOfWeek), $weekdays, true)) {
                    continue;
                }

                if ($candidate->greaterThan($now)) {
                    return $candidate;
                }
            }
        } catch (Throwable) {
            return null;
        }

        return null;
    }

    private static function longestRecentRun(Collection $dates, CarbonImmutable $today): int
    {
        $dates = $dates->map(fn ($date) => CarbonImmutable::parse($date)->toDateString())->unique();
        $cursor = $today;
        $count = 0;

        while ($dates->contains($cursor->toDateString())) {
            $count++;
            $cursor = $cursor->subDay();
        }

        return $count;
    }

    private static function digestSentence(int $prayerDays, int $devotionalDays, int $bibleDays, int $journalCount, ?string $topTopic, int $topTopicCount, int $consistentDays): string
    {
        $parts = [
            "You prayed {$prayerDays} ".str('time')->plural($prayerDays),
            "completed {$devotionalDays} devotional ".str('reading')->plural($devotionalDays),
            "read Scripture on {$bibleDays} ".str('day')->plural($bibleDays),
        ];

        if ($topTopic) {
            $parts[] = 'journaled about '.str($topTopic)->headline()->lower()." {$topTopicCount} ".str('time')->plural($topTopicCount);
        } elseif ($journalCount > 0) {
            $parts[] = "wrote {$journalCount} journal ".str('entry')->plural($journalCount);
        }

        $parts[] = "stayed consistent for {$consistentDays} ".str('day')->plural($consistentDays);

        return implode(', ', $parts).'.';
    }
}
