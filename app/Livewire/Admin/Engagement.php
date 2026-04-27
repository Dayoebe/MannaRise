<?php

namespace App\Livewire\Admin;

use App\Models\Devotional;
use App\Models\DevotionalCompletion;
use App\Models\User;
use Livewire\Component;
use Livewire\WithPagination;

class Engagement extends Component
{
    use WithPagination;

    public function render()
    {
        return view('livewire.admin.engagement', [
            'users' => User::query()
                ->withCount([
                    'journalEntries',
                    'prayerRequests',
                    'testimonies',
                    'favoriteDevotionals as favorites_count',
                    'devotionalCompletions as completions_count',
                ])
                ->orderBy('name')
                ->paginate(12),
            'topDevotionals' => Devotional::query()
                ->with('category')
                ->withCount(['favoritedBy as favorites_count', 'completions'])
                ->orderByDesc('completions_count')
                ->take(8)
                ->get(),
            'completionCountThisWeek' => DevotionalCompletion::where('completed_on', '>=', now()->subWeek()->toDateString())->count(),
        ]);
    }
}
