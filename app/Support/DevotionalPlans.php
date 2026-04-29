<?php

namespace App\Support;

use App\Models\Devotional;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;

class DevotionalPlans
{
    /**
     * @return array<string, array<string, mixed>>
     */
    public static function all(): array
    {
        return [
            '7-days-of-courage' => [
                'slug' => '7-days-of-courage',
                'title' => '7 Days of Courage',
                'duration' => 7,
                'description' => 'A one-week path for choosing faith, obedience, and steadiness when the next step feels costly.',
                'categories' => ['faith', 'hope', 'leadership'],
                'accent' => 'amber',
                'focuses' => ['Strength', 'Trust', 'Boldness', 'Presence', 'Endurance', 'Wisdom', 'Obedience'],
            ],
            '21-days-of-prayer' => [
                'slug' => '21-days-of-prayer',
                'title' => '21 Days of Prayer',
                'duration' => 21,
                'description' => 'Three weeks of Scripture, reflection, and prayer rhythms for a deeper secret place.',
                'categories' => ['prayer', 'peace', 'spiritual-growth'],
                'accent' => 'rose',
                'focuses' => ['Stillness', 'Gratitude', 'Confession', 'Listening', 'Intercession', 'Surrender', 'Consistency'],
            ],
            '30-days-of-purpose' => [
                'slug' => '30-days-of-purpose',
                'title' => '30 Days of Purpose',
                'duration' => 30,
                'description' => 'A month-long plan for aligning work, calling, service, and decisions with God.',
                'categories' => ['purpose', 'business', 'wisdom', 'leadership'],
                'accent' => 'emerald',
                'focuses' => ['Calling', 'Stewardship', 'Service', 'Clarity', 'Integrity', 'Diligence', 'Fruitfulness'],
            ],
        ];
    }

    /**
     * @return array<string, mixed>|null
     */
    public static function find(string $slug): ?array
    {
        return self::all()[$slug] ?? null;
    }

    /**
     * @param  array<string, mixed>  $plan
     * @return Collection<int, array<string, mixed>>
     */
    public static function days(array $plan): Collection
    {
        $devotionals = Devotional::query()
            ->with('category')
            ->published()
            ->whereHas('category', fn ($query) => $query->whereIn('slug', $plan['categories']))
            ->oldest('published_at')
            ->get();

        if ($devotionals->isEmpty()) {
            $devotionals = Devotional::query()
                ->with('category')
                ->published()
                ->oldest('published_at')
                ->get();
        }

        $focuses = collect($plan['focuses']);
        $duration = (int) $plan['duration'];

        return collect(range(1, $duration))->map(function (int $day) use ($devotionals, $focuses): array {
            $devotional = $devotionals->isNotEmpty()
                ? $devotionals->values()[($day - 1) % $devotionals->count()]
                : null;
            $focus = $focuses[($day - 1) % $focuses->count()];

            return [
                'day_number' => $day,
                'focus' => $focus,
                'title' => $devotional?->title ?: "Day {$day}: {$focus}",
                'reference' => $devotional?->bible_reference ?: 'Psalm 119:105',
                'summary' => $devotional
                    ? Str::limit(strip_tags($devotional->content), 160)
                    : 'Read the Scripture slowly, write one response, and choose one faithful action for today.',
                'devotional' => $devotional,
            ];
        });
    }
}
