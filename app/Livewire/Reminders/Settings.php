<?php

namespace App\Livewire\Reminders;

use App\Models\DevotionalReminder;
use App\Support\Toast;
use Livewire\Component;

class Settings extends Component
{
    public string $title = 'Daily devotional reminder';

    public string $remind_at = '06:00';

    public string $timezone = 'Africa/Lagos';

    public array $days = ['monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday', 'sunday'];

    public array $reminderTypes = ['devotional', 'bible', 'prayer', 'missed', 'digest'];

    public bool $email_enabled = true;

    public bool $push_enabled = false;

    public bool $is_active = true;

    public function toggleChannel(string $field): void
    {
        if (! in_array($field, ['email_enabled', 'push_enabled', 'is_active'], true)) {
            return;
        }

        $this->{$field} = ! $this->{$field};
    }

    public function toggleDay(string $day): void
    {
        $allowed = ['monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday', 'sunday'];

        if (! in_array($day, $allowed, true)) {
            return;
        }

        $this->days = in_array($day, $this->days, true)
            ? array_values(array_diff($this->days, [$day]))
            : array_values(array_unique([...$this->days, $day]));
    }

    public function toggleReminderType(string $type): void
    {
        $allowed = ['devotional', 'bible', 'journal', 'prayer', 'plan', 'memory', 'group', 'missed', 'digest'];

        if (! in_array($type, $allowed, true)) {
            return;
        }

        $this->reminderTypes = in_array($type, $this->reminderTypes, true)
            ? array_values(array_diff($this->reminderTypes, [$type]))
            : array_values(array_unique([...$this->reminderTypes, $type]));
    }

    public function mount(): void
    {
        $reminder = DevotionalReminder::where('user_id', auth()->id())->first();

        if (! $reminder) {
            return;
        }

        $this->title = $reminder->title;
        $this->remind_at = substr($reminder->remind_at, 0, 5);
        $this->timezone = $reminder->timezone;
        $this->days = $reminder->days['weekdays'] ?? ($reminder->days ?? $this->days);
        $this->reminderTypes = $reminder->days['types'] ?? $this->reminderTypes;
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
            'days' => ['array'],
            'days.*' => ['in:monday,tuesday,wednesday,thursday,friday,saturday,sunday'],
            'reminderTypes' => ['array'],
            'reminderTypes.*' => ['in:devotional,bible,journal,prayer,plan,memory,group,missed,digest'],
            'email_enabled' => ['boolean'],
            'push_enabled' => ['boolean'],
            'is_active' => ['boolean'],
        ]);

        DevotionalReminder::updateOrCreate(
            ['user_id' => auth()->id()],
            [
                'title' => $validated['title'],
                'timezone' => $validated['timezone'],
                'email_enabled' => $validated['email_enabled'],
                'push_enabled' => $validated['push_enabled'],
                'is_active' => $validated['is_active'],
                'remind_at' => $validated['remind_at'].':00',
                'days' => [
                    'weekdays' => array_values($validated['days']),
                    'types' => array_values($validated['reminderTypes']),
                ],
            ]
        );

        Toast::status($this, 'Reminder preferences saved.');
    }

    public function render()
    {
        return view('livewire.reminders.settings', [
            'weekdayOptions' => [
                'monday' => 'Mon',
                'tuesday' => 'Tue',
                'wednesday' => 'Wed',
                'thursday' => 'Thu',
                'friday' => 'Fri',
                'saturday' => 'Sat',
                'sunday' => 'Sun',
            ],
            'typeOptions' => [
                'devotional' => ['Daily devotional', 'sparkles'],
                'bible' => ['Bible reading', 'book-open'],
                'journal' => ['Journal reflection', 'journal'],
                'prayer' => ['Prayer rhythm', 'heart'],
                'plan' => ['Plan continuation', 'route'],
                'memory' => ['Memory verse', 'award'],
                'group' => ['Group prayer', 'users'],
                'missed' => ['Missed-day nudges', 'refresh-cw'],
                'digest' => ['Weekly spiritual digest', 'bar-chart'],
            ],
        ]);
    }
}
