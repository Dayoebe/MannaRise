<?php

namespace App\Livewire;

use App\Models\Devotional;
use Carbon\CarbonImmutable;
use Livewire\Component;

class Dashboard extends Component
{
    private function readingStreak(): int
    {
        $dates = auth()->user()
            ->devotionalCompletions()
            ->select('completed_on')
            ->distinct()
            ->latest('completed_on')
            ->pluck('completed_on')
            ->map(fn ($date) => CarbonImmutable::parse($date)->toDateString())
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

    public function render()
    {
        $user = auth()->user();

        return view('livewire.dashboard', [
            'stats' => [
                'favorites' => $user->favoriteDevotionals()->count(),
                'journal_entries' => $user->journalEntries()->count(),
                'prayer_requests' => $user->prayerRequests()->count(),
                'completed' => $user->devotionalCompletions()->count(),
                'streak' => $this->readingStreak(),
            ],
            'todayDevotional' => Devotional::with('category')->published()->latest('published_at')->first(),
            'recentJournalEntries' => $user->journalEntries()->with('devotional')->latest('entry_date')->take(4)->get(),
            'recentFavorites' => $user->favoriteDevotionals()->with('category')->latest('devotional_favorites.created_at')->take(4)->get(),
        ]);
    }
}
