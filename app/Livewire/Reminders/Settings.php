<?php

namespace App\Livewire\Reminders;

use App\Models\DevotionalReminder;
use Livewire\Component;

class Settings extends Component
{
    public string $title = 'Daily devotional reminder';
    public string $remind_at = '06:00';
    public string $timezone = 'Africa/Lagos';
    public bool $email_enabled = true;
    public bool $push_enabled = false;
    public bool $is_active = true;

    public function mount(): void
    {
        $reminder = DevotionalReminder::where('user_id', auth()->id())->first();

        if (! $reminder) {
            return;
        }

        $this->title = $reminder->title;
        $this->remind_at = substr($reminder->remind_at, 0, 5);
        $this->timezone = $reminder->timezone;
        $this->email_enabled = $reminder->email_enabled;
        $this->push_enabled = $reminder->push_enabled;
        $this->is_active = $reminder->is_active;
    }

    public function save(): void
    {
        $validated = $this->validate([
            'title' => ['required', 'string', 'max:255'],
            'remind_at' => ['required', 'date_format:H:i'],
            'timezone' => ['required', 'string', 'max:64'],
            'email_enabled' => ['boolean'],
            'push_enabled' => ['boolean'],
            'is_active' => ['boolean'],
        ]);

        DevotionalReminder::updateOrCreate(
            ['user_id' => auth()->id()],
            [
                ...$validated,
                'remind_at' => $validated['remind_at'].':00',
            ]
        );

        session()->flash('status', 'Reminder preferences saved.');
    }

    public function render()
    {
        return view('livewire.reminders.settings');
    }
}
