<?php

namespace App\Livewire\Daily;

use App\Models\DailyScripture;
use App\Support\DailySpiritualRhythm;
use App\Support\GrowthAnalytics;
use App\Support\LanguagePages;
use Carbon\CarbonImmutable;
use Throwable;
use Livewire\Component;

class Show extends Component
{
    public string $dailyDate;

    public ?string $locale = null;

    public function mount(string $date, ?string $locale = null): void
    {
        abort_unless($locale === null || LanguagePages::isSupported($locale), 404);

        try {
            $parsedDate = CarbonImmutable::createFromFormat('!Y-m-d', $date);
        } catch (Throwable) {
            $parsedDate = false;
        }

        abort_unless($parsedDate && $parsedDate->format('Y-m-d') === $date, 404);

        $this->dailyDate = $parsedDate->toDateString();
        $this->locale = $locale;

        GrowthAnalytics::trackDailyPageView(
            request(),
            $parsedDate,
            $this->language(),
            $this->shareId($parsedDate),
        );
    }

    public function render()
    {
        $date = $this->date();
        $dailyRhythm = DailySpiritualRhythm::forDate($date);
        $scripture = $this->scripture($dailyRhythm, $date);
        $copy = LanguagePages::dailyCopy($this->locale ?: 'en', $dailyRhythm, $date);
        $language = $this->language();
        $affirmation = [
            ...$dailyRhythm['affirmation'],
            'text' => $copy['affirmation_text'],
            'reference' => $copy['affirmation_reference'],
        ];
        $reflection = [
            ...$dailyRhythm['reflection'],
            'prayer' => $copy['prayer'],
            'journal_prompt' => $copy['journal_prompt'],
            'action' => $copy['action'],
            'theme_label' => $copy['theme_label'],
        ];
        $permalink = $this->locale
            ? route('daily.localized.show', ['locale' => $this->locale, 'date' => $date->toDateString()])
            : route('daily.show', ['date' => $date->toDateString()]);
        $shareId = $this->shareId($date);
        $trackedShareUrl = GrowthAnalytics::trackedShareUrl($permalink, $language, $date, $shareId);

        return view('livewire.daily.show', [
            'date' => $date,
            'dailyRhythm' => $dailyRhythm,
            'scripture' => $scripture,
            'affirmation' => $affirmation,
            'reflection' => $reflection,
            'permalink' => $permalink,
            'defaultPermalink' => route('daily.show', ['date' => $date->toDateString()]),
            'copy' => $copy,
            'languageOptions' => LanguagePages::dailyOptions($this->locale ?: 'en', $date),
            'card' => [
                'title' => $copy['page_title'],
                'date' => $copy['date_label'],
                'scripture_text' => $scripture['text'],
                'scripture_reference' => $scripture['reference'],
                'affirmation' => $affirmation['text'],
                'affirmation_reference' => $affirmation['reference'],
                'prayer' => $reflection['prayer'],
                'journal_prompt' => $reflection['journal_prompt'],
                'theme' => $reflection['theme_label'],
                'url' => $permalink,
                'share_url' => $trackedShareUrl,
                'app_url' => rtrim((string) config('app.url'), '/') ?: 'MannaRise',
                'analytics' => [
                    'language' => $language,
                    'daily_date' => $date->toDateString(),
                    'share_id' => $shareId,
                    'endpoint' => route('analytics.events'),
                    'csrf' => csrf_token(),
                ],
                'labels' => [
                    'daily_devotion' => $copy['card_devotion_label'],
                    'affirmation' => mb_strtoupper($copy['affirmation_label']),
                    'prayer' => mb_strtoupper($copy['prayer_label']),
                    'journal_prompt' => mb_strtoupper($copy['journal_label']),
                    'growth' => $copy['card_growth_label'],
                ],
                'status' => [
                    'downloaded' => $copy['status_downloaded'],
                    'copy_unavailable' => $copy['status_copy_unavailable'],
                    'copied' => $copy['status_copied'],
                    'native_unavailable' => $copy['status_native_unavailable'],
                    'shared' => $copy['status_shared'],
                    'not_completed' => $copy['status_not_completed'],
                    'whatsapp' => $copy['status_whatsapp'],
                ],
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
        $fallback = DailySpiritualRhythm::fallbackScriptureForTheme((string) ($affirmation['theme'] ?? 'peace'));

        return [
            'text' => $fallback['text'],
            'reference' => $fallback['reference'],
            'book_slug' => null,
            'chapter' => null,
        ];
    }

    private function date(): CarbonImmutable
    {
        return CarbonImmutable::createFromFormat('!Y-m-d', $this->dailyDate);
    }

    private function language(): string
    {
        return $this->locale ?: 'en';
    }

    private function shareId(CarbonImmutable $date): string
    {
        return GrowthAnalytics::makeShareId('daily|'.$this->language().'|'.$date->toDateString());
    }
}
