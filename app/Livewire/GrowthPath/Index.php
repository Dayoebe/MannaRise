<?php

namespace App\Livewire\GrowthPath;

use App\Models\PersonalizedDailyPathCheckIn;
use App\Models\UserSpiritualProfile;
use App\Support\PersonalizedDailyPath;
use App\Support\SpiritualConnectionInsight;
use App\Support\Toast;
use Carbon\CarbonImmutable;
use Livewire\Component;

class Index extends Component
{
    public string $season = 'peace';

    public string $path_goal = '';

    public string $support_note = '';

    public string $preferred_time = '';

    public function mount(): void
    {
        $profile = auth()->user()
            ->spiritualProfile()
            ->firstOrCreate([], ['season' => 'peace']);

        $this->season = $profile->season;
        $this->path_goal = (string) ($profile->path_goal ?? '');
        $this->support_note = (string) ($profile->support_note ?? '');
        $this->preferred_time = (string) ($profile->preferred_time ?? '');
    }

    public function saveSeason(): void
    {
        $validated = $this->validate([
            'season' => ['required', 'in:'.implode(',', array_keys(PersonalizedDailyPath::seasons()))],
            'path_goal' => ['nullable', 'string', 'max:120'],
            'support_note' => ['nullable', 'string', 'max:500'],
            'preferred_time' => ['nullable', 'string', 'max:32'],
        ]);

        UserSpiritualProfile::updateOrCreate(
            ['user_id' => auth()->id()],
            [
                'season' => $validated['season'],
                'path_goal' => $validated['path_goal'] ?: null,
                'support_note' => $validated['support_note'] ?: null,
                'preferred_time' => $validated['preferred_time'] ?: null,
            ],
        );

        Toast::status($this, 'Your daily path was updated.');
    }

    public function completeStep(string $step): void
    {
        $columns = [
            'devotional' => 'devotional_completed_at',
            'scripture' => 'scripture_completed_at',
            'affirmation' => 'affirmation_completed_at',
            'prayer' => 'prayer_completed_at',
            'journal' => 'journal_completed_at',
            'action' => 'action_completed_at',
        ];

        abort_unless(array_key_exists($step, $columns), 404);

        $path = PersonalizedDailyPath::forSeason($this->season);
        $definition = $path['definition'];

        $checkIn = PersonalizedDailyPathCheckIn::firstOrCreate(
            [
                'user_id' => auth()->id(),
                'checked_on' => CarbonImmutable::today()->toDateString(),
            ],
            [
                'season_key' => $path['key'],
                'devotional_id' => $path['devotional']?->id,
                'bible_reference' => $definition['reference'],
            ],
        );

        $column = $columns[$step];
        $checkIn->forceFill([
            'season_key' => $path['key'],
            'devotional_id' => $path['devotional']?->id,
            'bible_reference' => $definition['reference'],
            $column => $checkIn->{$column} ? null : now(),
        ])->save();
    }

    public function switchToSuggestedPath(string $season): void
    {
        abort_unless(array_key_exists($season, PersonalizedDailyPath::seasons()), 404);

        $this->season = $season;
        $this->saveSeason();
    }

    public function render()
    {
        $path = PersonalizedDailyPath::forSeason($this->season);
        $definition = $path['definition'];
        $checkIn = PersonalizedDailyPathCheckIn::firstOrCreate(
            [
                'user_id' => auth()->id(),
                'checked_on' => CarbonImmutable::today()->toDateString(),
            ],
            [
                'season_key' => $path['key'],
                'devotional_id' => $path['devotional']?->id,
                'bible_reference' => $definition['reference'],
            ],
        );

        if ($checkIn->season_key !== $path['key']) {
            $checkIn->forceFill([
                'season_key' => $path['key'],
                'devotional_id' => $path['devotional']?->id,
                'bible_reference' => $definition['reference'],
            ])->save();
        }

        return view('livewire.growth-path.index', [
            'seasons' => PersonalizedDailyPath::seasons(),
            'path' => $path,
            'checkIn' => $checkIn,
            'insight' => SpiritualConnectionInsight::forUser(auth()->user(), $this->season),
        ]);
    }
}
