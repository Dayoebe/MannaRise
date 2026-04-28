<?php

namespace App\Livewire\PrayerRequests;

use App\Models\PrayerRequest;
use App\Models\PrayerRoomMembership;
use App\Models\PrayerRoomPrayer;
use Illuminate\Support\Carbon;
use Livewire\Component;
use Livewire\WithPagination;

class Wall extends Component
{
    use WithPagination;

    public string $search = '';

    public string $status = 'open';

    public function updatedSearch(): void
    {
        $this->resetPage();
    }

    public function updatedStatus(): void
    {
        $this->resetPage();
    }

    public function pray(int $id): void
    {
        $request = PrayerRequest::where('is_public', true)->findOrFail($id);
        $request->increment('prayed_count');

        if (auth()->check() && $request->prayer_room_id) {
            PrayerRoomPrayer::create([
                'user_id' => auth()->id(),
                'prayer_room_id' => $request->prayer_room_id,
                'prayer_request_id' => $request->id,
                'prayed_on' => today(),
            ]);

            $this->updatePrayerRoomStreak($request->prayer_room_id);
        }

        session()->flash('status', 'Prayer count updated.');
    }

    public function render()
    {
        return view('livewire.prayer-requests.wall', [
            'requests' => PrayerRequest::query()
                ->where('is_public', true)
                ->with('room')
                ->when($this->status === 'open', fn ($query) => $query->where('is_answered', false))
                ->when($this->status === 'answered', fn ($query) => $query->where('is_answered', true))
                ->when($this->search !== '', fn ($query) => $query->where(function ($query) {
                    $query
                        ->where('title', 'like', "%{$this->search}%")
                        ->orWhere('body', 'like', "%{$this->search}%")
                        ->orWhere('name', 'like', "%{$this->search}%");
                }))
                ->latest()
                ->paginate(9),
            'openCount' => PrayerRequest::where('is_public', true)->where('is_answered', false)->count(),
            'answeredCount' => PrayerRequest::where('is_public', true)->where('is_answered', true)->count(),
        ]);
    }

    private function updatePrayerRoomStreak(int $roomId): void
    {
        $membership = PrayerRoomMembership::query()->firstOrCreate(
            [
                'user_id' => auth()->id(),
                'prayer_room_id' => $roomId,
            ],
            ['joined_at' => now()],
        );

        $today = today();
        $lastPrayedOn = $membership->last_prayed_on
            ? Carbon::parse($membership->last_prayed_on)->startOfDay()
            : null;

        if ($lastPrayedOn?->isSameDay($today)) {
            $currentStreak = $membership->current_streak;
        } elseif ($lastPrayedOn?->isSameDay($today->copy()->subDay())) {
            $currentStreak = $membership->current_streak + 1;
        } else {
            $currentStreak = 1;
        }

        $membership->update([
            'last_prayed_on' => $today,
            'current_streak' => $currentStreak,
            'longest_streak' => max($membership->longest_streak, $currentStreak),
            'total_prayers' => $membership->total_prayers + 1,
        ]);
    }
}
