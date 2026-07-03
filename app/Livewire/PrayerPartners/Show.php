<?php

namespace App\Livewire\PrayerPartners;

use App\Models\PrayerPartnerRoom;
use App\Support\GrowthAnalytics;
use App\Support\LanguagePreference;
use Carbon\CarbonImmutable;
use Livewire\Component;

class Show extends Component
{
    public PrayerPartnerRoom $room;

    public bool $prayed = false;

    public string $status = '';

    public function mount(string $token): void
    {
        $this->room = PrayerPartnerRoom::query()
            ->where('token', $token)
            ->firstOrFail();

        $this->room->increment('visits_count', 1, ['last_visited_at' => now()]);
        $this->room->refresh();

        GrowthAnalytics::track('prayer_partner_room_view', request(), [
            'language' => $this->room->language ?: LanguagePreference::current(),
            'daily_date' => $this->room->sourceDateForAnalytics(),
            'share_id' => $this->room->share_id,
        ]);
    }

    public function markPrayed(): void
    {
        if ($this->prayed) {
            return;
        }

        $this->room->increment('prayed_count');
        $this->room->refresh();
        $this->prayed = true;
        $this->status = 'Prayer marked. Your partner will see that this room has been prayed through.';

        GrowthAnalytics::track('prayer_partner_prayed', request(), [
            'language' => $this->room->language ?: LanguagePreference::current(),
            'daily_date' => $this->room->sourceDateForAnalytics(),
            'share_id' => $this->room->share_id,
        ]);
    }

    public function render()
    {
        $shareUrl = $this->shareUrl();

        return view('livewire.prayer-partners.show', [
            'shareUrl' => $shareUrl,
            'shareText' => $this->shareText(),
        ]);
    }

    private function shareUrl(): string
    {
        $date = $this->room->dailyDate();

        return GrowthAnalytics::trackedReferralUrl(
            route('prayer-partners.show', ['token' => $this->room->token]),
            'share_link',
            $this->room->language ?: LanguagePreference::current(),
            $date instanceof CarbonImmutable ? $date : null,
            $this->room->share_id,
            [
                'share' => 'prayer-partner-room',
                'utm_campaign' => 'prayer-partner-room',
                'utm_content' => $this->room->source_key ?: $this->room->token,
            ],
        );
    }

    private function shareText(): string
    {
        return trim("Invite someone to pray with you today\n\n{$this->room->title}\n\n{$this->room->summary}");
    }
}
