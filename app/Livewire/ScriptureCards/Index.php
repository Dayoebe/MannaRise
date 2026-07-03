<?php

namespace App\Livewire\ScriptureCards;

use App\Models\Devotional;
use App\Models\PrayerRequest;
use App\Models\Testimony;
use App\Support\DailySpiritualRhythm;
use App\Support\LanguagePages;
use App\Support\LanguagePreference;
use App\Support\LocalizedDailyScripture;
use Carbon\CarbonImmutable;
use Illuminate\Support\Str;
use Livewire\Component;

class Index extends Component
{
    public function render()
    {
        $dailyRhythm = DailySpiritualRhythm::forDate();
        $language = LanguagePreference::current();
        $date = $this->dailyDate($dailyRhythm);
        $copy = LanguagePages::dailyCopy($language, $dailyRhythm, $date);
        $navCopy = LanguagePreference::navCopy($language);

        return view('livewire.scripture-cards.index', [
            'appUrl' => $this->appUrl(),
            'cardFooter' => $navCopy['grow_daily'],
            'cards' => [
                'verse' => $this->verseCards($dailyRhythm, $language, $date, $copy),
                'affirmation' => $this->affirmationCards($dailyRhythm, $copy),
                'devotional' => $this->devotionalCards($copy),
                'prayer' => $this->prayerCards($copy),
                'testimony' => $this->testimonyCards(),
                'note' => $this->noteCards($date),
            ],
        ]);
    }

    /**
     * @param  array<string, mixed>  $dailyRhythm
     * @return array<int, array<string, string>>
     */
    private function verseCards(array $dailyRhythm, string $language, CarbonImmutable $date, array $copy): array
    {
        $scripture = LocalizedDailyScripture::forDate($dailyRhythm, $date, $language);

        return [[
            'label' => $copy['scripture_label'],
            'title' => $copy['scripture_label'],
            'text' => $scripture['text'],
            'reference' => $scripture['reference'],
            'date' => $copy['date_label'],
            'kind' => $copy['scripture_label'],
        ]];
    }

    /**
     * @param  array<string, mixed>  $dailyRhythm
     * @return array<int, array<string, string>>
     */
    private function affirmationCards(array $dailyRhythm, array $copy): array
    {
        $affirmation = $dailyRhythm['affirmation'];

        return [[
            'label' => $copy['affirmation_label'],
            'title' => $copy['affirmation_label'],
            'text' => $copy['affirmation_text'],
            'reference' => $affirmation['reference'],
            'date' => $copy['date_label'],
            'kind' => $copy['affirmation_label'],
        ]];
    }

    /**
     * @return array<int, array<string, string>>
     */
    private function devotionalCards(array $copy): array
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
            'label' => $copy['page_eyebrow'],
            'title' => $copy['page_title'],
            'text' => $copy['page_intro'],
            'reference' => 'MannaRise',
            'date' => $copy['date_label'],
            'kind' => $copy['card_devotion_label'],
        ]];
    }

    /**
     * @return array<int, array<string, string>>
     */
    private function prayerCards(array $copy): array
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
            'label' => $copy['prayer_label'],
            'title' => $copy['prayer_label'],
            'text' => $copy['prayer'],
            'reference' => 'MannaRise',
            'date' => $copy['date_label'],
            'kind' => $copy['prayer_label'],
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
    private function noteCards(CarbonImmutable $date): array
    {
        return [[
            'label' => 'Note card',
            'title' => 'Personal note',
            'text' => 'Write what is on your heart, then download it as a MannaRise card.',
            'reference' => 'MannaRise note',
            'date' => LanguagePages::dateLabel(LanguagePreference::current(), $date),
            'kind' => 'Note',
        ]];
    }

    /**
     * @param  array<string, mixed>  $dailyRhythm
     */
    private function dailyDate(array $dailyRhythm): CarbonImmutable
    {
        $date = $dailyRhythm['date'] ?? now();

        return $date instanceof CarbonImmutable
            ? $date
            : CarbonImmutable::parse($date);
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
