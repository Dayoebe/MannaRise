<?php

namespace App\Livewire\GrowthPath;

use App\Models\UserSpiritualProfile;
use App\Support\PersonalizedDailyPath;
use Livewire\Component;

class Index extends Component
{
    public string $season = 'peace';

    public function mount(): void
    {
        $this->season = auth()->user()
            ->spiritualProfile()
            ->firstOrCreate([], ['season' => 'peace'])
            ->season;
    }

    public function saveSeason(): void
    {
        $validated = $this->validate([
            'season' => ['required', 'in:'.implode(',', array_keys(PersonalizedDailyPath::seasons()))],
        ]);

        UserSpiritualProfile::updateOrCreate(
            ['user_id' => auth()->id()],
            ['season' => $validated['season']],
        );

        session()->flash('status', 'Your daily path was updated.');
    }

    public function render()
    {
        return view('livewire.growth-path.index', [
            'seasons' => PersonalizedDailyPath::seasons(),
            'path' => PersonalizedDailyPath::forSeason($this->season),
        ]);
    }
}
