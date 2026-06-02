<?php

namespace App\Livewire\Devotionals;

use App\Models\Devotional;
use App\Models\DevotionalCompletion;
use App\Models\DevotionalFavorite;
use App\Models\JournalEntry;
use App\Support\Toast;
use Livewire\Component;

class Show extends Component
{
    public Devotional $devotional;

    public string $journalTitle = '';

    public string $journalContent = '';

    public function mount(string $slug): void
    {
        $this->devotional = Devotional::with(['category', 'author'])
            ->published()
            ->where('slug', $slug)
            ->firstOrFail();

        $this->journalTitle = 'Reflection on '.$this->devotional->title;
    }

    public function toggleFavorite()
    {
        if (! auth()->check()) {
            return redirect()->route('login');
        }

        $favorite = DevotionalFavorite::where('user_id', auth()->id())
            ->where('devotional_id', $this->devotional->id)
            ->first();

        if ($favorite) {
            $favorite->delete();
            Toast::status($this, 'Removed from favorites.');

            return null;
        }

        DevotionalFavorite::create([
            'user_id' => auth()->id(),
            'devotional_id' => $this->devotional->id,
        ]);

        Toast::status($this, 'Saved to favorites.');

        return null;
    }

    public function markCompleted()
    {
        if (! auth()->check()) {
            return redirect()->route('login');
        }

        DevotionalCompletion::firstOrCreate([
            'user_id' => auth()->id(),
            'devotional_id' => $this->devotional->id,
            'completed_on' => today()->toDateString(),
        ]);

        Toast::status($this, 'Marked as completed for today.');

        return null;
    }

    public function saveJournalEntry()
    {
        if (! auth()->check()) {
            return redirect()->route('login');
        }

        $validated = $this->validate([
            'journalTitle' => ['required', 'string', 'max:255'],
            'journalContent' => ['required', 'string', 'min:10'],
        ]);

        JournalEntry::create([
            'user_id' => auth()->id(),
            'devotional_id' => $this->devotional->id,
            'title' => $validated['journalTitle'],
            'content' => $validated['journalContent'],
            'entry_date' => today()->toDateString(),
        ]);

        $this->journalContent = '';

        Toast::status($this, 'Journal entry saved.');

        return null;
    }

    public function render()
    {
        $isFavorited = auth()->check()
            ? DevotionalFavorite::where('user_id', auth()->id())->where('devotional_id', $this->devotional->id)->exists()
            : false;

        $completedToday = auth()->check()
            ? DevotionalCompletion::where('user_id', auth()->id())
                ->where('devotional_id', $this->devotional->id)
                ->whereDate('completed_on', today())
                ->exists()
            : false;

        return view('livewire.devotionals.show', [
            'isFavorited' => $isFavorited,
            'completedToday' => $completedToday,
            'summary' => \App\Support\Seo::summarize($this->devotional->content),
            'relatedDevotionals' => Devotional::query()
                ->with('category')
                ->published()
                ->whereKeyNot($this->devotional->id)
                ->when($this->devotional->devotional_category_id, fn ($query) => $query->where('devotional_category_id', $this->devotional->devotional_category_id))
                ->latest('published_at')
                ->take(3)
                ->get(),
        ]);
    }
}
