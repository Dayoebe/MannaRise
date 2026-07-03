<?php

namespace App\Livewire\PrayerInvites;

use App\Models\Devotional;
use App\Models\PrayerPartnerRoom;
use App\Support\GrowthAnalytics;
use App\Support\LanguagePreference;
use App\Support\Seo;
use Livewire\Component;

class Show extends Component
{
    public ?Devotional $devotional = null;

    public function mount(?string $devotionalSlug = null): void
    {
        $this->trackReferralView();

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
        $partnerRoom = PrayerPartnerRoom::fromInvite($this->devotional, request());
        $baseInviteUrl = route('prayer-partners.show', ['token' => $partnerRoom->token]);
        $shareId = is_string(request()->query('sid'))
            ? request()->query('sid')
            : ($partnerRoom->share_id ?: GrowthAnalytics::makeShareId('prayer-invite|'.($this->devotional?->slug ?: 'general')));
        $inviteUrl = GrowthAnalytics::trackedReferralUrl($baseInviteUrl, 'share_link', $partnerRoom->language ?: LanguagePreference::current(), $partnerRoom->dailyDate(), $shareId, [
            'share' => 'prayer-partner-room',
            'utm_campaign' => 'prayer-partner-room',
            'utm_content' => $partnerRoom->source_key ?: 'general',
        ]);

        $summary = $this->devotional
            ? Seo::summarize($this->devotional->content, 36)
            : 'Take a few minutes to pray with me through MannaRise. We can pause, read Scripture, and bring the day before God.';

        return view('livewire.prayer-invites.show', [
            'inviteUrl' => $inviteUrl,
            'partnerRoom' => $partnerRoom,
            'partnerRoomUrl' => $baseInviteUrl,
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

    private function trackReferralView(): void
    {
        if (! GrowthAnalytics::isReferralRequest(request())) {
            return;
        }

        GrowthAnalytics::track('prayer_invite_view', request(), [
            'language' => is_string(request()->query('lang')) ? request()->query('lang') : LanguagePreference::current(),
        ]);
    }
}
