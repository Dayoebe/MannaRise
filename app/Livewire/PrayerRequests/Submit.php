<?php

namespace App\Livewire\PrayerRequests;

use App\Models\PrayerRequest;
use Livewire\Component;

class Submit extends Component
{
    public string $name = '';

    public string $email = '';

    public string $title = '';

    public string $body = '';

    public bool $is_public = false;

    public function mount(): void
    {
        if (auth()->check()) {
            $this->name = auth()->user()->name;
            $this->email = auth()->user()->email;
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
        ]);

        PrayerRequest::create([
            ...$validated,
            'user_id' => auth()->id(),
        ]);

        $this->reset('title', 'body', 'is_public');
        session()->flash('status', 'Prayer request submitted.');
    }

    public function render()
    {
        return view('livewire.prayer-requests.submit');
    }
}
