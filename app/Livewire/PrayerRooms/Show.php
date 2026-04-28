<?php

namespace App\Livewire\PrayerRooms;

use App\Models\PrayerRequest;
use App\Models\PrayerRequestUpdate;
use App\Models\PrayerRoom;
use App\Models\PrayerRoomMembership;
use App\Models\PrayerRoomPrayer;
use Illuminate\Support\Carbon;
use Livewire\Component;
use Livewire\WithPagination;

class Show extends Component
{
    use WithPagination;

    public PrayerRoom $prayerRoom;

    public string $search = '';

    public string $status = 'open';

    public ?int $answeringRequestId = null;

    public string $answeredUpdateBody = '';

    public function mount(string $room): void
    {
        PrayerRoom::syncDefaults();

        $this->prayerRoom = PrayerRoom::where('slug', $room)
            ->where('is_active', true)
            ->firstOrFail();
    }

    public function updatedSearch(): void
    {
        $this->resetPage();
    }

    public function updatedStatus(): void
    {
        $this->resetPage();
    }

    public function join()
    {
        if (! auth()->check()) {
            return redirect()->route('login');
        }

        PrayerRoomMembership::query()->firstOrCreate(
            [
                'user_id' => auth()->id(),
                'prayer_room_id' => $this->prayerRoom->id,
            ],
            ['joined_at' => now()],
        );

        session()->flash('status', "You joined the {$this->prayerRoom->name} prayer room.");
    }

    public function leave(): void
    {
        if (! auth()->check()) {
            return;
        }

        PrayerRoomMembership::query()
            ->where('user_id', auth()->id())
            ->where('prayer_room_id', $this->prayerRoom->id)
            ->delete();

        session()->flash('status', "You left the {$this->prayerRoom->name} prayer room.");
    }

    public function pray(int $id)
    {
        if (! auth()->check()) {
            return redirect()->route('login');
        }

        $request = PrayerRequest::query()
            ->where('prayer_room_id', $this->prayerRoom->id)
            ->where('is_public', true)
            ->findOrFail($id);

        $request->increment('prayed_count');

        PrayerRoomPrayer::create([
            'user_id' => auth()->id(),
            'prayer_room_id' => $this->prayerRoom->id,
            'prayer_request_id' => $request->id,
            'prayed_on' => today(),
        ]);

        $this->updatePrayerStreak();

        session()->flash('status', 'Prayer logged and streak updated.');
    }

    public function beginAnsweredUpdate(int $id): void
    {
        $request = $this->editableRequest($id);

        $this->answeringRequestId = $request->id;
        $this->answeredUpdateBody = '';
        $this->resetValidation();
    }

    public function cancelAnsweredUpdate(): void
    {
        $this->answeringRequestId = null;
        $this->answeredUpdateBody = '';
        $this->resetValidation();
    }

    public function addAnsweredUpdate(int $id): void
    {
        $request = $this->editableRequest($id);

        $validated = $this->validate([
            'answeredUpdateBody' => ['required', 'string', 'min:3', 'max:1000'],
        ]);

        PrayerRequestUpdate::create([
            'prayer_request_id' => $request->id,
            'user_id' => auth()->id(),
            'body' => $validated['answeredUpdateBody'],
            'is_answered_update' => true,
        ]);

        $request->update(['is_answered' => true]);

        $this->answeringRequestId = null;
        $this->answeredUpdateBody = '';

        session()->flash('status', 'Answered-prayer update shared.');
    }

    public function render()
    {
        $membership = null;

        if (auth()->check()) {
            $membership = PrayerRoomMembership::query()
                ->where('user_id', auth()->id())
                ->where('prayer_room_id', $this->prayerRoom->id)
                ->first();
        }

        return view('livewire.prayer-rooms.show', [
            'room' => $this->prayerRoom,
            'membership' => $membership,
            'requests' => PrayerRequest::query()
                ->where('prayer_room_id', $this->prayerRoom->id)
                ->where('is_public', true)
                ->with(['updates' => fn ($query) => $query->latest(), 'user'])
                ->when($this->status === 'open', fn ($query) => $query->where('is_answered', false))
                ->when($this->status === 'answered', fn ($query) => $query->where('is_answered', true))
                ->when($this->search !== '', fn ($query) => $query->where(function ($query) {
                    $query
                        ->where('title', 'like', "%{$this->search}%")
                        ->orWhere('body', 'like', "%{$this->search}%")
                        ->orWhere('name', 'like', "%{$this->search}%");
                }))
                ->latest()
                ->paginate(8),
            'openCount' => $this->prayerRoom->publicRequests()->where('is_answered', false)->count(),
            'answeredCount' => $this->prayerRoom->publicRequests()->where('is_answered', true)->count(),
            'todayPrayerCount' => $this->prayerRoom->prayers()->whereDate('prayed_on', today())->count(),
        ]);
    }

    private function editableRequest(int $id): PrayerRequest
    {
        abort_unless(auth()->check(), 403);

        $request = PrayerRequest::query()
            ->where('prayer_room_id', $this->prayerRoom->id)
            ->where('is_public', true)
            ->findOrFail($id);

        $user = auth()->user();

        abort_unless($request->user_id === $user->id || $user->hasAdminAccess(), 403);

        return $request;
    }

    private function updatePrayerStreak(): void
    {
        $membership = PrayerRoomMembership::query()->firstOrCreate(
            [
                'user_id' => auth()->id(),
                'prayer_room_id' => $this->prayerRoom->id,
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
