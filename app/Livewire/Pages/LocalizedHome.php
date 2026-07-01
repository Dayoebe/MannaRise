<?php

namespace App\Livewire\Pages;

use App\Support\DailySpiritualRhythm;
use App\Support\LanguagePages;
use App\Support\LocalizedDailyScripture;
use Carbon\CarbonImmutable;
use Livewire\Component;

class LocalizedHome extends Component
{
    public string $locale;

    public function mount(string $locale): void
    {
        abort_unless(LanguagePages::isSupported($locale), 404);

        $this->locale = $locale;
    }

    public function render()
    {
        $date = CarbonImmutable::today();
        $dailyRhythm = DailySpiritualRhythm::forDate($date);

        return view('livewire.pages.localized-home', [
            'date' => $date,
            'content' => LanguagePages::landingContent($this->locale, $dailyRhythm, $date),
            'dailyRhythm' => $dailyRhythm,
            'scripture' => LocalizedDailyScripture::forDate($dailyRhythm, $date, $this->locale),
        ]);
    }
}
