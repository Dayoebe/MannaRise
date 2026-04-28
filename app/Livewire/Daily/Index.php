<?php

namespace App\Livewire\Daily;

use App\Support\DailySpiritualRhythm;
use Carbon\CarbonImmutable;
use Livewire\Component;

class Index extends Component
{
    public function render()
    {
        $today = CarbonImmutable::today();

        return view('livewire.daily.index', [
            'dailyRhythm' => DailySpiritualRhythm::forDate($today),
            'upcomingPlans' => DailySpiritualRhythm::challengePreview($today, 7),
        ]);
    }
}
