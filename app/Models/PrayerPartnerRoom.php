<?php

namespace App\Models;

use App\Support\DailySpiritualRhythm;
use App\Support\GrowthAnalytics;
use App\Support\LanguagePages;
use App\Support\LanguagePreference;
use App\Support\LocalizedDailyScripture;
use App\Support\Seo;
use Carbon\CarbonImmutable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Throwable;

class PrayerPartnerRoom extends Model
{
    protected $fillable = [
        'token',
        'source_type',
        'source_key',
        'language',
        'share_id',
        'title',
        'summary',
        'scripture_reference',
        'scripture_text',
        'prayer_focus',
        'journal_prompt',
        'source_url',
        'visits_count',
        'prayed_count',
        'last_visited_at',
        'metadata',
    ];

    protected function casts(): array
    {
        return [
            'metadata' => 'array',
            'visits_count' => 'integer',
            'prayed_count' => 'integer',
            'last_visited_at' => 'datetime',
        ];
    }

    public static function fromInvite(?Devotional $devotional, Request $request): self
    {
        $language = self::languageFromRequest($request);
        $date = self::dailyDateFromRequest($request);
        $sourceType = $devotional ? 'devotional' : ($date ? 'daily' : 'general');
        $sourceKey = $devotional?->slug ?: $date?->toDateString();
        $shareId = self::shareIdFor($sourceType, $sourceKey, $language, $request);
        $token = GrowthAnalytics::makeShareId("prayer-partner|{$sourceType}|{$sourceKey}|{$language}|{$shareId}");

        return self::query()->firstOrCreate(
            ['token' => $token],
            [
                ...self::contentFor($devotional, $date, $language),
                'source_type' => $sourceType,
                'source_key' => $sourceKey,
                'language' => $language,
                'share_id' => $shareId,
                'metadata' => [
                    'ref_code' => is_string($request->query('ref')) ? Str::limit($request->query('ref'), 80, '') : null,
                    'utm_campaign' => is_string($request->query('utm_campaign')) ? Str::limit($request->query('utm_campaign'), 128, '') : null,
                ],
            ],
        );
    }

    public function dailyDate(): ?CarbonImmutable
    {
        if ($this->source_type !== 'daily' || ! $this->source_key) {
            return null;
        }

        return self::parseDate($this->source_key);
    }

    public function sourceDateForAnalytics(): ?string
    {
        return $this->source_type === 'daily' ? $this->source_key : null;
    }

    public function sourceLabel(): string
    {
        return match ($this->source_type) {
            'daily' => $this->source_key ? "Daily devotion for {$this->source_key}" : 'Daily devotion',
            'devotional' => 'Shared devotional',
            default => 'Shared prayer moment',
        };
    }

    /**
     * @return array<string, mixed>
     */
    private static function contentFor(?Devotional $devotional, ?CarbonImmutable $date, string $language): array
    {
        if ($devotional) {
            $summary = Seo::summarize($devotional->content, 42);

            return [
                'title' => "Pray with me: {$devotional->title}",
                'summary' => $summary,
                'scripture_reference' => $devotional->bible_reference,
                'scripture_text' => $devotional->bible_text,
                'prayer_focus' => $devotional->prayer_point ?: "Father, let this devotion become more than words. Help us receive it, pray honestly, and encourage one another today.",
                'journal_prompt' => $devotional->reflection_question ?: 'What is one honest prayer this devotion is inviting us to pray today?',
                'source_url' => route('devotionals.show', $devotional->slug),
            ];
        }

        if ($date) {
            $dailyRhythm = DailySpiritualRhythm::forDate($date);
            $scripture = LocalizedDailyScripture::forDate($dailyRhythm, $date, $language);
            $copy = LanguagePages::dailyCopy($language, $dailyRhythm, $date);

            return [
                'title' => $copy['page_title'],
                'summary' => $copy['page_intro'].' Theme: '.$copy['theme_label'].'.',
                'scripture_reference' => $scripture['reference'],
                'scripture_text' => $scripture['text'],
                'prayer_focus' => $copy['prayer'],
                'journal_prompt' => $copy['journal_prompt'],
                'source_url' => $language === 'en'
                    ? route('daily.show', ['date' => $date->toDateString()])
                    : route('daily.localized.show', ['locale' => $language, 'date' => $date->toDateString()]),
            ];
        }

        return [
            'title' => 'Pray with me today',
            'summary' => 'A public MannaRise prayer room for sharing one focused moment of Scripture, prayer, and encouragement.',
            'scripture_reference' => 'Matthew 18:20 KJV',
            'scripture_text' => 'For where two or three are gathered together in my name, there am I in the midst of them.',
            'prayer_focus' => 'Father, meet us as we pray together today. Give us faith, clarity, peace, and love for the person on the other side of this invitation.',
            'journal_prompt' => 'What are we trusting God with today?',
            'source_url' => route('daily.index'),
        ];
    }

    private static function shareIdFor(string $sourceType, ?string $sourceKey, string $language, Request $request): string
    {
        $shareId = $request->query('sid') ?: $request->query('share_id');

        if (is_string($shareId) && trim($shareId) !== '') {
            return Str::limit(trim($shareId), 80, '');
        }

        return GrowthAnalytics::makeShareId("{$sourceType}|{$sourceKey}|{$language}");
    }

    private static function languageFromRequest(Request $request): string
    {
        $language = $request->query('lang') ?: $request->route('locale') ?: LanguagePreference::current();
        $language = is_string($language) ? strtolower(Str::limit($language, 12, '')) : 'en';

        return LanguagePages::isSupported($language) ? $language : 'en';
    }

    private static function dailyDateFromRequest(Request $request): ?CarbonImmutable
    {
        $date = $request->query('daily_date');

        return is_string($date) ? self::parseDate($date) : null;
    }

    private static function parseDate(string $date): ?CarbonImmutable
    {
        try {
            $parsedDate = CarbonImmutable::createFromFormat('!Y-m-d', $date);
        } catch (Throwable) {
            $parsedDate = false;
        }

        if (! $parsedDate || $parsedDate->format('Y-m-d') !== $date) {
            return null;
        }

        return $parsedDate;
    }
}
