<?php

namespace App\Livewire\PrayerRooms;

use App\Models\PrayerRoom;
use App\Models\PrayerRoomPrayer;
use Livewire\Component;

class Index extends Component
{
    public function mount(): void
    {
        PrayerRoom::syncDefaults();
    }

    public function render()
    {
        return view('livewire.prayer-rooms.index', [
            'rooms' => PrayerRoom::query()
                ->where('is_active', true)
                ->withCount([
                    'memberships',
                    'publicRequests as open_requests_count' => fn ($query) => $query->where('is_answered', false),
                    'publicRequests as answered_requests_count' => fn ($query) => $query->where('is_answered', true),
                ])
                ->orderBy('sort_order')
                ->get(),
            'todayPrayerCount' => PrayerRoomPrayer::whereDate('prayed_on', today())->count(),
        ]);
    }
}
