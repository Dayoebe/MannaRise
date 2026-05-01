<?php

namespace App\Support;

use App\Models\BibleChapterCompletion;
use App\Models\DevotionalPlanCompletion;
use App\Models\JournalEntry;
use App\Models\PrayerRequest;
use App\Models\User;
use App\Models\UserBibleReadingHistory;
use App\Models\UserBibleVerseEngagement;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;

class SpiritualConnectionInsight
{
    public static function forUser(User $user, ?string $season = null): array
    {
        $since = now()->subDays(14);
        $season = array_key_exists($season ?? '', PersonalizedDailyPath::seasons()) ? $season : ($user->spiritualProfile?->season ?? 'peace');
        $definition = PersonalizedDailyPath::seasons()[$season] ?? PersonalizedDailyPath::seasons()['peace'];

        $journalEntries = JournalEntry::query()
            ->where('user_id', $user->id)
            ->where('created_at', '>=', $since)
            ->latest()
            ->get();

        $prayerRequests = PrayerRequest::query()
            ->where('user_id', $user->id)
            ->where('created_at', '>=', $since)
            ->latest()
            ->get();

        $verseEngagements = UserBibleVerseEngagement::query()
            ->with('verse.book')
            ->where('user_id', $user->id)
            ->where(function ($query) use ($since): void {
                $query->where('updated_at', '>=', $since)
                    ->orWhere('highlighted_at', '>=', $since)
                    ->orWhere('bookmarked_at', '>=', $since)
                    ->orWhere('note_updated_at', '>=', $since);
            })
            ->latest('updated_at')
            ->get();

        $readings = UserBibleReadingHistory::query()
            ->with('book')
            ->where('user_id', $user->id)
            ->where('last_read_at', '>=', $since)
            ->latest('last_read_at')
            ->get();

        $chapterCompletions = BibleChapterCompletion::query()
            ->with('book')
            ->where('user_id', $user->id)
            ->where('completed_at', '>=', $since)
            ->latest('completed_at')
            ->get();

        $planCompletions = DevotionalPlanCompletion::query()
            ->where('user_id', $user->id)
            ->where('completed_on', '>=', Carbon::today()->subDays(14)->toDateString())
            ->latest('completed_on')
            ->get();

        $text = Str::lower(collect()
            ->merge($journalEntries->map(fn (JournalEntry $entry) => $entry->title.' '.$entry->content.' '.collect($entry->topics ?? [])->join(' ')))
            ->merge($prayerRequests->map(fn (PrayerRequest $request) => $request->title.' '.$request->body))
            ->merge($verseEngagements->map(fn (UserBibleVerseEngagement $engagement) => $engagement->note.' '.$engagement->verse?->text))
            ->join(' '));

        $matchedSeason = collect(PersonalizedDailyPath::seasons())
            ->mapWithKeys(fn (array $item, string $key) => [
                $key => collect($item['terms'])->sum(fn (string $term) => Str::contains($text, Str::lower($term)) ? 1 : 0),
            ])
            ->sortDesc()
            ->keys()
            ->first();

        if ($matchedSeason && $matchedSeason !== $season && $text !== '') {
            $matchedDefinition = PersonalizedDailyPath::seasons()[$matchedSeason];
            $primary = "Your recent prayers and reflections lean toward {$matchedDefinition['label']}. You can switch today's path there if that fits your real season.";
            $suggestedSeason = $matchedSeason;
        } elseif ($verseEngagements->isNotEmpty()) {
            $primary = 'You have been marking Scripture recently. Turn one highlighted verse into today&apos;s journal response so the reading becomes prayer and obedience.';
            $suggestedSeason = $season;
        } elseif ($readings->isNotEmpty() || $chapterCompletions->isNotEmpty()) {
            $primary = 'You have Bible reading momentum. Pair the next chapter with the prayer and action step from this path.';
            $suggestedSeason = $season;
        } elseif ($journalEntries->isNotEmpty()) {
            $primary = 'Your journal has fresh reflections. Use today&apos;s Scripture focus to name one next step from what you wrote.';
            $suggestedSeason = $season;
        } else {
            $primary = "Start with {$definition['reference']}, then complete the prayer and journal prompt so MannaRise can connect your next recommendations.";
            $suggestedSeason = $season;
        }

        return [
            'primary' => $primary,
            'suggested_season' => $suggestedSeason,
            'stats' => [
                'chapters' => $readings->count(),
                'marked_verses' => $verseEngagements->count(),
                'journal_entries' => $journalEntries->count(),
                'prayer_requests' => $prayerRequests->count(),
                'plan_days' => $planCompletions->count(),
            ],
            'latest_reading' => $readings->first(),
            'latest_marked_verse' => $verseEngagements->first(),
        ];
    }
}
