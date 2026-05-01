<?php

namespace App\Support;

use App\Models\Devotional;
use App\Models\DevotionalCategory;
use App\Models\JournalEntry;
use App\Models\PrayerRequest;
use App\Models\PrayerRoom;
use App\Models\PrayerRoomPrayer;
use App\Models\UserBibleVerseEngagement;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;

class ContentPlanningIntelligence
{
    /**
     * @return array<int, array<string, string>>
     */
    public static function suggestions(): array
    {
        return collect()
            ->merge(self::categoryGaps())
            ->merge(self::activePrayerRoomSuggestions())
            ->merge(self::journalAndPrayerTopicSuggestions())
            ->merge(self::readerSignalSuggestions())
            ->take(10)
            ->values()
            ->all();
    }

    private static function categoryGaps(): Collection
    {
        $importantTopics = [
            'grief' => 'grief and loss',
            'anxiety' => 'peace and anxiety',
            'family' => 'family restoration',
            'marriage' => 'marriage',
            'business' => 'business and stewardship',
            'healing' => 'healing',
            'salvation' => 'salvation',
            'purpose' => 'purpose and calling',
        ];

        return collect($importantTopics)->map(function (string $label, string $term): ?array {
            $recentExists = Devotional::query()
                ->published()
                ->where('published_at', '>=', now()->subMonth())
                ->where(function ($query) use ($term, $label): void {
                    $query->where('title', 'like', "%{$term}%")
                        ->orWhere('content', 'like', "%{$term}%")
                        ->orWhere('title', 'like', "%{$label}%")
                        ->orWhereHas('category', fn ($category) => $category
                            ->where('name', 'like', "%{$term}%")
                            ->orWhere('slug', 'like', "%{$term}%"));
                })
                ->exists();

            if ($recentExists) {
                return null;
            }

            return [
                'type' => 'Content gap',
                'title' => "No recent devotional for {$label}",
                'detail' => "Publish or feature a devotional around {$label}; this keeps life-event support fresh.",
                'action' => 'Create devotional',
            ];
        })->filter()->values();
    }

    private static function activePrayerRoomSuggestions(): Collection
    {
        $activeRooms = PrayerRoom::query()
            ->where('is_active', true)
            ->withCount([
                'prayers as recent_prayers_count' => fn ($query) => $query->where('prayed_on', '>=', now()->subDays(14)),
                'requests as open_requests_count' => fn ($query) => $query->where('is_answered', false),
            ])
            ->orderByDesc('recent_prayers_count')
            ->take(4)
            ->get();

        return $activeRooms
            ->filter(fn (PrayerRoom $room) => $room->recent_prayers_count > 0 || $room->open_requests_count > 0)
            ->map(fn (PrayerRoom $room) => [
                'type' => 'Prayer room signal',
                'title' => "{$room->name} is active",
                'detail' => "{$room->recent_prayers_count} recent prayers and {$room->open_requests_count} open requests. Feature content that speaks to this room.",
                'action' => 'Feature related content',
            ]);
    }

    private static function journalAndPrayerTopicSuggestions(): Collection
    {
        $journalTopics = JournalEntry::query()
            ->where('entry_date', '>=', now()->subDays(30)->toDateString())
            ->get()
            ->flatMap(fn (JournalEntry $entry) => collect($entry->topics ?? []))
            ->map(fn ($topic) => Str::lower(trim((string) $topic)))
            ->filter()
            ->countBy();

        $prayerText = PrayerRequest::query()
            ->where('created_at', '>=', now()->subDays(30))
            ->get(['title', 'body'])
            ->map(fn (PrayerRequest $request) => Str::lower($request->title.' '.$request->body))
            ->join(' ');

        $prayerTopics = collect(['family', 'healing', 'peace', 'business', 'marriage', 'exams', 'salvation', 'grief', 'purpose'])
            ->mapWithKeys(fn (string $topic) => [$topic => substr_count($prayerText, $topic)])
            ->filter();

        return $journalTopics
            ->mergeRecursive($prayerTopics)
            ->map(fn ($count) => is_array($count) ? array_sum($count) : $count)
            ->sortDesc()
            ->take(4)
            ->map(fn (int $count, string $topic) => [
                'type' => 'User need',
                'title' => str($topic)->headline().' is showing up',
                'detail' => "{$count} recent journal or prayer signals mention this topic. Plan content, cards, or guided prayer around it.",
                'action' => 'Plan topic cluster',
            ])
            ->values();
    }

    private static function readerSignalSuggestions(): Collection
    {
        $markedText = UserBibleVerseEngagement::query()
            ->with('verse')
            ->where('updated_at', '>=', now()->subDays(30))
            ->get()
            ->map(fn (UserBibleVerseEngagement $engagement) => Str::lower(($engagement->note ?? '').' '.($engagement->verse?->text ?? '')))
            ->join(' ');

        return collect(['peace', 'faith', 'hope', 'love', 'wisdom', 'healing'])
            ->mapWithKeys(fn (string $topic) => [$topic => substr_count($markedText, $topic)])
            ->filter()
            ->sortDesc()
            ->take(3)
            ->map(fn (int $count, string $topic) => [
                'type' => 'Bible reader signal',
                'title' => "Readers are marking verses about {$topic}",
                'detail' => "{$count} recent marked-verse signals mention {$topic}. Consider a devotional plan, Scripture card set, or featured reading.",
                'action' => 'Create follow-up',
            ])
            ->values();
    }
}
