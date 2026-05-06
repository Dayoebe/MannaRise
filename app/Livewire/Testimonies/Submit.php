<?php

namespace App\Livewire\Testimonies;

use App\Models\Testimony;
use App\Support\Toast;
use Livewire\Component;

class Submit extends Component
{
    public string $name = '';

    public string $title = '';

    public string $body = '';

    public bool $is_anonymous = false;

    public function mount(): void
    {
        if (auth()->check()) {
            $this->name = auth()->user()->name;
        }
    }

    public function submit(): void
    {
        $validated = $this->validate([
            'name' => ['nullable', 'string', 'max:255'],
            'title' => ['required', 'string', 'max:255'],
            'body' => ['required', 'string', 'min:10'],
            'is_anonymous' => ['boolean'],
        ]);

        Testimony::create([
            ...$validated,
            'user_id' => auth()->id(),
            'is_approved' => false,
        ]);

        $this->reset('title', 'body', 'is_anonymous');
        Toast::status($this, 'Testimony submitted for review.');
    }

    public function render()
    {
        return view('livewire.testimonies.submit');
    }
}
