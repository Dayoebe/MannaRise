<?php

namespace App\Livewire\PrayerRequests;

use App\Models\PrayerRequest;
use App\Models\PrayerRoom;
use App\Support\Toast;
use Livewire\Component;

class Submit extends Component
{
    public string $name = '';

    public string $email = '';

    public string $title = '';

    public string $body = '';

    public bool $is_public = false;

    public string $room = '';

    public function mount(): void
    {
        PrayerRoom::syncDefaults();

        if (auth()->check()) {
            $this->name = auth()->user()->name;
            $this->email = auth()->user()->email;
        }

        $room = request()->query('room');

        if (is_string($room)) {
            $selectedRoom = PrayerRoom::where('slug', $room)->where('is_active', true)->first();
            $this->room = $selectedRoom ? (string) $selectedRoom->id : '';
        }
    }

    public function submit(): void
    {
        $validated = $this->validate([
            'name' => ['nullable', 'string', 'max:255'],
            'email' => ['nullable', 'email', 'max:255'],
            'title' => ['required', 'string', 'max:255'],
            'body' => ['required', 'string', 'min:10'],
            'is_public' => ['boolean'],
            'room' => ['nullable', 'exists:prayer_rooms,id'],
        ]);

        PrayerRequest::create([
            'user_id' => auth()->id(),
            'prayer_room_id' => $validated['room'] ?: null,
            'name' => $validated['name'],
            'email' => $validated['email'],
            'title' => $validated['title'],
            'body' => $validated['body'],
            'is_public' => $validated['is_public'],
        ]);

        $this->reset('title', 'body', 'is_public');
        Toast::status($this, 'Prayer request submitted.');
    }

    public function render()
    {
        PrayerRoom::syncDefaults();

        return view('livewire.prayer-requests.submit', [
            'rooms' => PrayerRoom::where('is_active', true)->orderBy('sort_order')->get(),
        ]);
    }
}
