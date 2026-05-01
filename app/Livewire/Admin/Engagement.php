<?php

namespace App\Livewire\Admin;

use App\Models\Devotional;
use App\Models\DevotionalCategory;
use App\Models\DevotionalCompletion;
use App\Models\User;
use App\Support\ContentPlanningIntelligence;
use Illuminate\Support\Str;
use Livewire\Component;
use Livewire\WithPagination;

class Engagement extends Component
{
    use WithPagination;

    public function createDraftFromSuggestion(string $title, string $detail): void
    {
        $category = DevotionalCategory::firstOrCreate(
            ['slug' => 'content-planning'],
            [
                'name' => 'Content Planning',
                'description' => 'Drafts created from admin content planning intelligence.',
                'is_active' => true,
            ],
        );

        $draftTitle = Str::limit(str_replace(['No recent devotional for ', ' is showing up', ' is active'], '', $title), 80, '');
        $draftTitle = 'Draft: '.Str::headline($draftTitle ?: $title);

        Devotional::create([
            'devotional_category_id' => $category->id,
            'user_id' => auth()->id(),
            'title' => $draftTitle,
            'slug' => $this->uniqueDevotionalSlug($draftTitle),
            'content' => $detail."\n\nScripture focus:\n\nReflection:\n\nPrayer:\n\nAction step:",
            'reflection_question' => 'What part of this topic is God asking the reader to bring into the light?',
            'prayer_point' => 'Pray for grace, wisdom, and obedience in this area.',
            'declaration' => 'God meets me with truth, mercy, and strength for this season.',
            'published_at' => null,
            'is_featured' => false,
            'is_published' => false,
            'reading_time' => 5,
        ]);

        session()->flash('status', 'Content planning draft created.');
    }

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
            'contentSuggestions' => ContentPlanningIntelligence::suggestions(),
        ]);
    }

    private function uniqueDevotionalSlug(string $title): string
    {
        $base = Str::slug($title) ?: 'content-planning-draft';
        $slug = $base;
        $suffix = 2;

        while (Devotional::where('slug', $slug)->exists()) {
            $slug = "{$base}-{$suffix}";
            $suffix++;
        }

        return $slug;
    }
}
