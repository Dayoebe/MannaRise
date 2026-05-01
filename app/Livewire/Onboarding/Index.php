<?php

namespace App\Livewire\Onboarding;

use App\Models\DevotionalReminder;
use App\Models\UserSpiritualProfile;
use App\Support\DevotionalPlans;
use App\Support\PersonalizedDailyPath;
use Livewire\Component;

class Index extends Component
{
    public string $season = 'peace';

    public string $path_goal = '';

    public string $preferred_time = 'morning';

    public bool $daily_reminder = true;

    public bool $memory_reminder = true;

    public string $first_plan = '7-days-of-courage';

    public function mount(): void
    {
        $profile = auth()->user()->spiritualProfile()->firstOrCreate([], ['season' => 'peace']);

        $this->season = $profile->season ?: 'peace';
        $this->path_goal = (string) ($profile->path_goal ?? '');
        $this->preferred_time = (string) ($profile->preferred_time ?? 'morning');
    }

    public function finish()
    {
        $validated = $this->validate([
            'season' => ['required', 'in:'.implode(',', array_keys(PersonalizedDailyPath::seasons()))],
            'path_goal' => ['nullable', 'string', 'max:120'],
            'preferred_time' => ['required', 'in:morning,midday,evening'],
            'daily_reminder' => ['boolean'],
            'memory_reminder' => ['boolean'],
            'first_plan' => ['required', 'in:'.implode(',', array_keys(DevotionalPlans::all()))],
        ]);

        UserSpiritualProfile::updateOrCreate(
            ['user_id' => auth()->id()],
            [
                'season' => $validated['season'],
                'path_goal' => $validated['path_goal'] ?: null,
                'preferred_time' => $validated['preferred_time'],
                'onboarding_completed_at' => now(),
            ],
        );

        if ($validated['daily_reminder']) {
            DevotionalReminder::updateOrCreate(
                ['user_id' => auth()->id()],
                [
                    'title' => 'Daily MannaRise rhythm',
                    'remind_at' => match ($validated['preferred_time']) {
                        'midday' => '12:00:00',
                        'evening' => '19:00:00',
                        default => '06:00:00',
                    },
                    'timezone' => config('app.timezone', 'Africa/Lagos'),
                    'days' => ['monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday', 'sunday'],
                    'email_enabled' => true,
                    'push_enabled' => false,
                    'is_active' => true,
                ],
            );
        }

        session()->flash('status', 'Your MannaRise path is ready.');

        return redirect()->route('devotional-plans.show', $validated['first_plan']);
    }

    public function skip()
    {
        auth()->user()->spiritualProfile()->firstOrCreate([], ['season' => 'peace'])->update([
            'onboarding_completed_at' => now(),
        ]);

        return redirect()->route('dashboard');
    }

    public function render()
    {
        return view('livewire.onboarding.index', [
            'seasons' => PersonalizedDailyPath::seasons(),
            'plans' => DevotionalPlans::all(),
        ]);
    }
}
