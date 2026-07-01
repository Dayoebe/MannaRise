<?php

namespace App\Livewire\ScriptureCards;

use App\Models\Devotional;
use App\Models\PrayerRequest;
use App\Models\Testimony;
use App\Support\DailySpiritualRhythm;
use Illuminate\Support\Str;
use Livewire\Component;

class Index extends Component
{
    public function render()
    {
        $dailyRhythm = DailySpiritualRhythm::forDate();

        return view('livewire.scripture-cards.index', [
            'appUrl' => $this->appUrl(),
            'cards' => [
                'verse' => $this->verseCards($dailyRhythm),
                'affirmation' => $this->affirmationCards($dailyRhythm),
                'devotional' => $this->devotionalCards(),
                'prayer' => $this->prayerCards(),
                'testimony' => $this->testimonyCards(),
                'note' => $this->noteCards(),
            ],
        ]);
    }

    /**
     * @param  array<string, mixed>  $dailyRhythm
     * @return array<int, array<string, string>>
     */
    private function verseCards(array $dailyRhythm): array
    {
        $verse = $dailyRhythm['verse'] ?? null;

        if (! $verse) {
            return [[
                'label' => 'Verse of the day',
                'title' => 'Verse of the day',
                'text' => 'Thy word is a lamp unto my feet, and a light unto my path.',
                'reference' => 'Psalm 119:105 KJV',
                'date' => now()->format('F j, Y'),
                'kind' => 'Verse',
            ]];
        }

        return [[
            'label' => 'Verse of the day',
            'title' => 'Verse of the day',
            'text' => $verse->text,
            'reference' => "{$verse->book->name} {$verse->chapter}:{$verse->verse} KJV",
            'date' => $dailyRhythm['date']->format('F j, Y'),
            'kind' => 'Verse',
        ]];
    }

    /**
     * @param  array<string, mixed>  $dailyRhythm
     * @return array<int, array<string, string>>
     */
    private function affirmationCards(array $dailyRhythm): array
    {
        $affirmation = $dailyRhythm['affirmation'];

        return [[
            'label' => 'Daily affirmation',
            'title' => 'Daily affirmation',
            'text' => $affirmation['text'],
            'reference' => $affirmation['reference'],
            'date' => $dailyRhythm['date']->format('F j, Y'),
            'kind' => 'Affirmation',
        ]];
    }

    /**
     * @return array<int, array<string, string>>
     */
    private function devotionalCards(): array
    {
        $cards = Devotional::query()
            ->published()
            ->latest('published_at')
            ->take(10)
            ->get()
            ->map(fn (Devotional $devotional): array => [
                'label' => $devotional->title,
                'title' => $devotional->title,
                'text' => $this->cardText($devotional->bible_text ?: $devotional->content),
                'reference' => $devotional->bible_reference ?: 'MannaRise devotional',
                'date' => $devotional->published_at?->format('F j, Y') ?? $devotional->created_at->format('F j, Y'),
                'kind' => 'Devotional',
            ])
            ->all();

        return $cards ?: [[
            'label' => 'Devotional',
            'title' => 'Today with God',
            'text' => 'Let this truth become practical today. Pause, listen, and choose one faithful act of obedience.',
            'reference' => 'MannaRise devotional',
            'date' => now()->format('F j, Y'),
            'kind' => 'Devotional',
        ]];
    }

    /**
     * @return array<int, array<string, string>>
     */
    private function prayerCards(): array
    {
        $cards = PrayerRequest::query()
            ->where('is_public', true)
            ->where('moderation_status', 'approved')
            ->latest()
            ->take(10)
            ->get()
            ->map(fn (PrayerRequest $request): array => [
                'label' => $request->title,
                'title' => $request->title,
                'text' => $this->cardText($request->body),
                'reference' => $request->is_answered ? 'Answered prayer' : 'Prayer request',
                'date' => $request->created_at->format('F j, Y'),
                'kind' => 'Prayer',
            ])
            ->all();

        return $cards ?: [[
            'label' => 'Prayer',
            'title' => 'Prayer',
            'text' => 'Lord, let Your peace guard every heart that is waiting, healing, rebuilding, or believing for breakthrough.',
            'reference' => 'MannaRise prayer',
            'date' => now()->format('F j, Y'),
            'kind' => 'Prayer',
        ]];
    }

    /**
     * @return array<int, array<string, string>>
     */
    private function testimonyCards(): array
    {
        $cards = Testimony::query()
            ->where('is_approved', true)
            ->latest()
            ->take(10)
            ->get()
            ->map(fn (Testimony $testimony): array => [
                'label' => $testimony->title,
                'title' => $testimony->title,
                'text' => $this->cardText($testimony->after_body ?: $testimony->body),
                'reference' => 'God did it - '.$testimony->categoryLabel(),
                'date' => ($testimony->answered_on ?: $testimony->created_at)->format('F j, Y'),
                'kind' => 'Testimony',
            ])
            ->all();

        return $cards ?: [[
            'label' => 'Testimony',
            'title' => 'God did it',
            'text' => 'This testimony began with prayer and became a reminder that God still meets people with mercy and power.',
            'reference' => 'MannaRise testimony',
            'date' => now()->format('F j, Y'),
            'kind' => 'Testimony',
        ]];
    }

    /**
     * @return array<int, array<string, string>>
     */
    private function noteCards(): array
    {
        return [[
            'label' => 'Note card',
            'title' => 'Personal note',
            'text' => 'Write what is on your heart, then download it as a MannaRise card.',
            'reference' => 'MannaRise note',
            'date' => now()->format('F j, Y'),
            'kind' => 'Note',
        ]];
    }

    private function cardText(?string $text): string
    {
        return Str::of(strip_tags($text ?? ''))
            ->squish()
            ->limit(220, '')
            ->toString();
    }

    private function appUrl(): string
    {
        $url = rtrim((string) config('app.url'), '/');

        return $url !== '' ? $url : 'MannaRise';
    }
}
