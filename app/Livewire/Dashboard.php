<?php

namespace App\Livewire;

use App\Models\DailyScripture;
use App\Models\Devotional;
use App\Models\DevotionalPlanCompletion;
use App\Models\DevotionalReminder;
use App\Models\MemoryVerseProgress;
use App\Models\User;
use App\Models\UserBibleReadingHistory;
use App\Support\DailySpiritualRhythm;
use App\Support\DevotionalPlans;
use App\Support\PersonalizedDailyPath;
use App\Support\SpiritualGrowthScore;
use App\Support\SpiritualRetentionSummary;
use Carbon\CarbonImmutable;
use Illuminate\Support\Collection;
use Livewire\Component;

class Dashboard extends Component
{
    private function streakFromDates(Collection $dates): int
    {
        $dates = $dates
            ->map(fn ($date) => CarbonImmutable::parse($date)->toDateString())
            ->unique()
            ->sortDesc()
            ->values();

        if ($dates->isEmpty()) {
            return 0;
        }

        $expected = CarbonImmutable::today();

        if ($dates->first() !== $expected->toDateString()) {
            $expected = $expected->subDay();

            if ($dates->first() !== $expected->toDateString()) {
                return 0;
            }
        }

        $streak = 0;

        foreach ($dates as $date) {
            if ($date !== $expected->toDateString()) {
                break;
            }

            $streak++;
            $expected = $expected->subDay();
        }

        return $streak;
    }

    /**
     * @return array<string, mixed>
     */
    private function unifiedProgress(User $user): array
    {
        $start = CarbonImmutable::today()->subDays(29);
        $weekStart = CarbonImmutable::today()->subDays(6);

        $bibleReadings = UserBibleReadingHistory::query()
            ->with('book')
            ->where('user_id', $user->id)
            ->where('last_read_at', '>=', $start)
            ->latest('last_read_at')
            ->get();
        $bibleCompletionDates = $user->bibleChapterCompletions()
            ->where('completed_at', '>=', $start)
            ->pluck('completed_at');
        $bibleDates = $bibleReadings->pluck('last_read_at')->merge($bibleCompletionDates);

        $prayerDates = $user->prayerRequests()
            ->where('created_at', '>=', $start)
            ->pluck('created_at')
            ->merge($user->prayerRoomPrayers()->where('prayed_on', '>=', $start)->pluck('prayed_on'));

        $journalEntries = $user->journalEntries()
            ->whereDate('entry_date', '>=', $start)
            ->latest('entry_date')
            ->get();

        $planRows = DevotionalPlanCompletion::query()
            ->where('user_id', $user->id)
            ->get()
            ->groupBy('plan_slug');
        $plans = collect(DevotionalPlans::all())->map(function (array $plan) use ($planRows): array {
            $completed = $planRows->get($plan['slug'], collect())->count();

            return [
                'slug' => $plan['slug'],
                'title' => $plan['title'],
                'completed' => $completed,
                'duration' => (int) $plan['duration'],
                'percent' => min(100, (int) round(($completed / max(1, (int) $plan['duration'])) * 100)),
            ];
        })->values();

        $topicCounts = $journalEntries
            ->flatMap(fn ($entry) => collect($entry->topics ?? []))
            ->map(fn ($topic) => strtolower((string) $topic))
            ->filter()
            ->countBy()
            ->sortDesc();

        $topTopic = $topicCounts->keys()->first();
        $journalPattern = $topTopic
            ? 'You have reflected most about '.str($topTopic)->headline()->lower().' in the last 30 days.'
            : ($journalEntries->isNotEmpty()
                ? 'Your journal rhythm is active. Add topics to reveal clearer spiritual patterns.'
                : 'Start with one honest journal entry this week and MannaRise will begin showing patterns.');

        $latestReading = $bibleReadings->first();
        $encouragement = match (true) {
            $bibleDates->isNotEmpty() && $prayerDates->isNotEmpty() && $journalEntries->isNotEmpty() => 'Your Bible, prayer, and reflection rhythms are beginning to work together. Keep the next step simple and consistent.',
            $bibleDates->isNotEmpty() => 'Your Bible reading has momentum. Pair the next chapter with a short prayer or journal response.',
            $prayerDates->isNotEmpty() => 'Prayer is present in your rhythm. Let Scripture shape the next request you bring before God.',
            $journalEntries->isNotEmpty() => 'Your reflections are active. Open the Bible reader and anchor one thought in Scripture.',
            default => 'Begin gently today: read one chapter, pray one honest prayer, and write one sentence.',
        };

        return [
            'stats' => [
                'bible_chapters' => $bibleReadings->count() + $bibleCompletionDates->count(),
                'bible_streak' => $this->streakFromDates($bibleDates),
                'prayer_days' => $prayerDates->map(fn ($date) => CarbonImmutable::parse($date)->toDateString())->unique()->count(),
                'prayer_streak' => $this->streakFromDates($prayerDates),
                'journal_entries' => $journalEntries->count(),
                'journal_streak' => $this->streakFromDates($journalEntries->pluck('entry_date')),
                'plan_days' => DevotionalPlanCompletion::where('user_id', $user->id)->count(),
                'memory_completed' => MemoryVerseProgress::where('user_id', $user->id)->whereNotNull('completed_at')->count(),
                'testimonies' => $user->testimonies()->count(),
                'weekly_bible_days' => $bibleDates->filter(fn ($date) => CarbonImmutable::parse($date)->gte($weekStart))->map(fn ($date) => CarbonImmutable::parse($date)->toDateString())->unique()->count(),
                'weekly_prayer_days' => $prayerDates->filter(fn ($date) => CarbonImmutable::parse($date)->gte($weekStart))->map(fn ($date) => CarbonImmutable::parse($date)->toDateString())->unique()->count(),
            ],
            'plans' => $plans,
            'journal_pattern' => $journalPattern,
            'encouragement' => $encouragement,
            'latest_reading' => $latestReading,
        ];
    }

    public function render()
    {
        $user = auth()->user();
        $unifiedProgress = $this->unifiedProgress($user);
        $reminder = DevotionalReminder::where('user_id', $user->id)->first();

        return view('livewire.dashboard', [
            'dailyRhythm' => DailySpiritualRhythm::forDate(),
            'todayScripture' => DailyScripture::query()->active()->forToday()->first(),
            'catchUpPlan' => DailySpiritualRhythm::catchUpPlanForUser($user),
            'growthScore' => SpiritualGrowthScore::forUser($user),
            'personalPath' => PersonalizedDailyPath::forSeason($user->spiritualProfile?->season),
            'stats' => [
                'favorites' => $user->favoriteDevotionals()->count(),
                'journal_entries' => $user->journalEntries()->count(),
                'prayer_requests' => $user->prayerRequests()->count(),
                'completed' => $user->devotionalCompletions()->count(),
                'streak' => $this->streakFromDates($user->devotionalCompletions()->pluck('completed_on')),
            ],
            'unifiedProgress' => $unifiedProgress,
            'retentionSummary' => [
                'weekly_digest' => SpiritualRetentionSummary::weeklyDigest($user),
                'next_reminder_at' => SpiritualRetentionSummary::nextReminderAt($reminder),
                'reminder' => $reminder,
            ],
            'todayDevotional' => Devotional::with('category')->published()->latest('published_at')->first(),
            'recentJournalEntries' => $user->journalEntries()->with('devotional')->latest('entry_date')->take(4)->get(),
            'recentFavorites' => $user->favoriteDevotionals()->with('category')->latest('devotional_favorites.created_at')->take(4)->get(),
        ]);
    }
}
