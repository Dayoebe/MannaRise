<?php

namespace App\Livewire\PrayerInvites;

use App\Models\Devotional;
use App\Support\Seo;
use Livewire\Component;

class Show extends Component
{
    public ?Devotional $devotional = null;

    public function mount(?string $devotionalSlug = null): void
    {
        if (! $devotionalSlug) {
            return;
        }

        $this->devotional = Devotional::query()
            ->with('category')
            ->published()
            ->where('slug', $devotionalSlug)
            ->firstOrFail();
    }

    public function render()
    {
        $inviteUrl = $this->devotional
            ? route('prayer-invites.show', ['devotionalSlug' => $this->devotional->slug])
            : route('prayer-invites.show');

        $summary = $this->devotional
            ? Seo::summarize($this->devotional->content, 36)
            : 'Take a few minutes to pray with me through MannaRise. We can pause, read Scripture, and bring the day before God.';

        return view('livewire.prayer-invites.show', [
            'inviteUrl' => $inviteUrl,
            'summary' => $summary,
            'shareText' => $this->shareText($summary),
        ]);
    }

    private function shareText(string $summary): string
    {
        $title = $this->devotional
            ? "Pray with me over {$this->devotional->title}"
            : 'Pray with me on MannaRise';

        return trim("{$title}\n\n{$summary}");
    }
}
