<?php

namespace App\Livewire\Devotionals;

use App\Models\Devotional;
use App\Models\DevotionalCompletion;
use App\Models\DevotionalFavorite;
use App\Models\JournalEntry;
use App\Support\GrowthAnalytics;
use App\Support\LanguagePreference;
use App\Support\Seo;
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

        if (GrowthAnalytics::isReferralRequest(request())) {
            GrowthAnalytics::track('devotional_page_view', request(), [
                'language' => is_string(request()->query('lang')) ? request()->query('lang') : LanguagePreference::current(),
            ]);
        }

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
            'summary' => Seo::summarize($this->devotional->content),
            'shareCard' => $this->shareCard(),
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

    /**
     * @return array<string, string>
     */
    private function shareCard(): array
    {
        $summary = Seo::summarize($this->devotional->content, 36);
        $language = LanguagePreference::current();
        $shareId = GrowthAnalytics::makeShareId('devotional|'.$this->devotional->slug);
        $shareUrl = GrowthAnalytics::trackedReferralUrl(route('devotionals.show', $this->devotional->slug), 'share_link', $language, null, $shareId, [
            'share' => 'devotional-card',
            'utm_campaign' => 'devotional-share',
            'utm_content' => $this->devotional->slug,
        ]);
        $inviteUrl = GrowthAnalytics::trackedReferralUrl(route('prayer-invites.show', ['devotionalSlug' => $this->devotional->slug]), 'pray_with_me', $language, null, $shareId, [
            'utm_campaign' => 'devotional-prayer-invite',
            'utm_content' => $this->devotional->slug,
        ]);

        return [
            'title' => $this->devotional->title,
            'text' => $this->devotional->bible_text ?: $summary,
            'summary' => $summary,
            'reference' => $this->devotional->bible_reference ?: 'MannaRise devotional',
            'date' => ($this->devotional->published_at ?: $this->devotional->created_at)->format('F j, Y'),
            'url' => $shareUrl,
            'invite_url' => $inviteUrl,
            'app_url' => rtrim((string) config('app.url'), '/') ?: 'MannaRise',
            'analytics_endpoint' => route('analytics.events'),
            'csrf' => csrf_token(),
            'language' => $language,
            'share_id' => $shareId,
        ];
    }
}
