<?php

namespace App\Livewire\Pages;

use App\Models\DailyScripture;
use App\Support\DailySpiritualRhythm;
use App\Support\LanguagePages;
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
            'scripture' => $this->scripture($dailyRhythm, $date),
        ]);
    }

    /**
     * @param  array<string, mixed>  $dailyRhythm
     * @return array{text:string,reference:string,book_slug:string|null,chapter:string|null}
     */
    private function scripture(array $dailyRhythm, CarbonImmutable $date): array
    {
        $stored = DailyScripture::query()
            ->active()
            ->whereDate('verse_date', $date->toDateString())
            ->first();

        if ($stored) {
            return [
                'text' => $stored->text,
                'reference' => trim($stored->reference.' '.strtoupper((string) $stored->translation)),
                'book_slug' => $stored->bibleRouteParameters()['book'] ?? null,
                'chapter' => $stored->chapter ? (string) $stored->chapter : null,
            ];
        }

        $verse = $dailyRhythm['verse'] ?? null;

        if ($verse) {
            return [
                'text' => $verse->text,
                'reference' => "{$verse->book->name} {$verse->chapter}:{$verse->verse} {$verse->version}",
                'book_slug' => $verse->book->slug,
                'chapter' => (string) $verse->chapter,
            ];
        }

        $affirmation = $dailyRhythm['affirmation'] ?? [];
        $fallback = DailySpiritualRhythm::fallbackScriptureForTheme((string) ($affirmation['theme'] ?? 'peace'));

        return [
            'text' => $fallback['text'],
            'reference' => $fallback['reference'],
            'book_slug' => null,
            'chapter' => null,
        ];
    }
}
