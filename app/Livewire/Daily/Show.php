<?php

namespace App\Livewire\Daily;

use App\Models\DailyScripture;
use App\Support\DailySpiritualRhythm;
use Carbon\CarbonImmutable;
use Throwable;
use Livewire\Component;

class Show extends Component
{
    public string $dailyDate;

    public ?string $locale = null;

    public function mount(string $date, ?string $locale = null): void
    {
        try {
            $parsedDate = CarbonImmutable::createFromFormat('!Y-m-d', $date);
        } catch (Throwable) {
            $parsedDate = false;
        }

        abort_unless($parsedDate && $parsedDate->format('Y-m-d') === $date, 404);

        $this->dailyDate = $parsedDate->toDateString();
        $this->locale = $locale;
    }

    public function render()
    {
        $date = $this->date();
        $dailyRhythm = DailySpiritualRhythm::forDate($date);
        $scripture = $this->scripture($dailyRhythm, $date);
        $affirmation = $dailyRhythm['affirmation'];
        $reflection = $dailyRhythm['reflection'];
        $permalink = $this->locale
            ? route('daily.localized.show', ['locale' => $this->locale, 'date' => $date->toDateString()])
            : route('daily.show', ['date' => $date->toDateString()]);

        return view('livewire.daily.show', [
            'date' => $date,
            'dailyRhythm' => $dailyRhythm,
            'scripture' => $scripture,
            'affirmation' => $affirmation,
            'reflection' => $reflection,
            'permalink' => $permalink,
            'defaultPermalink' => route('daily.show', ['date' => $date->toDateString()]),
            'card' => [
                'title' => 'MannaRise Daily Devotion',
                'date' => $date->format('F j, Y'),
                'scripture_text' => $scripture['text'],
                'scripture_reference' => $scripture['reference'],
                'affirmation' => $affirmation['text'],
                'affirmation_reference' => $affirmation['reference'],
                'prayer' => $reflection['prayer'],
                'journal_prompt' => $reflection['journal_prompt'],
                'theme' => $reflection['theme_label'],
                'url' => $permalink,
                'app_url' => rtrim((string) config('app.url'), '/') ?: 'MannaRise',
            ],
        ]);
    }

    /**
     * @param  array<string, mixed>  $dailyRhythm
     * @return array<string, string|null>
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

        return [
            ...$this->fallbackScripture((string) ($affirmation['theme'] ?? 'peace')),
            'book_slug' => null,
            'chapter' => null,
        ];
    }

    /**
     * @return array{text:string,reference:string}
     */
    private function fallbackScripture(string $theme): array
    {
        $fallbacks = [
            'wisdom' => ['reference' => 'James 1:5 KJV', 'text' => 'If any of you lack wisdom, let him ask of God, that giveth to all men liberally, and upbraideth not; and it shall be given him.'],
            'peace' => ['reference' => 'John 14:27 KJV', 'text' => 'Peace I leave with you, my peace I give unto you: not as the world giveth, give I unto you.'],
            'strength' => ['reference' => 'Isaiah 41:10 KJV', 'text' => 'Fear thou not; for I am with thee: be not dismayed; for I am thy God.'],
            'fruit' => ['reference' => 'Galatians 5:22-23 KJV', 'text' => 'The fruit of the Spirit is love, joy, peace, longsuffering, gentleness, goodness, faith, meekness, temperance.'],
            'renewal' => ['reference' => 'Isaiah 40:31 KJV', 'text' => 'They that wait upon the Lord shall renew their strength; they shall mount up with wings as eagles.'],
            'anxiety' => ['reference' => 'Philippians 4:6-7 KJV', 'text' => 'Be careful for nothing; but in every thing by prayer and supplication with thanksgiving let your requests be made known unto God.'],
            'purpose' => ['reference' => 'Ephesians 2:10 KJV', 'text' => 'For we are his workmanship, created in Christ Jesus unto good works.'],
            'word' => ['reference' => 'Psalm 119:105 KJV', 'text' => 'Thy word is a lamp unto my feet, and a light unto my path.'],
            'steadfast' => ['reference' => '1 Corinthians 15:58 KJV', 'text' => 'Be ye stedfast, unmoveable, always abounding in the work of the Lord.'],
            'mercy' => ['reference' => 'Lamentations 3:22-23 KJV', 'text' => 'It is of the Lord\'s mercies that we are not consumed, because his compassions fail not.'],
            'courage' => ['reference' => 'Joshua 1:9 KJV', 'text' => 'Be strong and of a good courage; be not afraid, neither be thou dismayed.'],
            'endurance' => ['reference' => 'Philippians 4:13 KJV', 'text' => 'I can do all things through Christ which strengtheneth me.'],
            'growth' => ['reference' => 'Colossians 2:7 KJV', 'text' => 'Rooted and built up in him, and stablished in the faith, as ye have been taught.'],
            'provision' => ['reference' => 'Psalm 23:1 KJV', 'text' => 'The Lord is my shepherd; I shall not want.'],
        ];

        return $fallbacks[$theme] ?? $fallbacks['peace'];
    }

    private function date(): CarbonImmutable
    {
        return CarbonImmutable::createFromFormat('!Y-m-d', $this->dailyDate);
    }
}
