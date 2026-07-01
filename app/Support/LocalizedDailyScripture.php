<?php

namespace App\Support;

use App\Models\BibleVerse;
use App\Models\DailyScripture;
use Carbon\CarbonImmutable;

class LocalizedDailyScripture
{
    /**
     * @param  array<string, mixed>  $dailyRhythm
     * @return array{text:string,reference:string,book_slug:string|null,chapter:string|null,language:string,version:string|null,is_localized:bool,reader_url:string|null}
     */
    public static function forDate(array $dailyRhythm, CarbonImmutable $date, string $language): array
    {
        $stored = DailyScripture::query()
            ->active()
            ->whereDate('verse_date', $date->toDateString())
            ->first();

        if ($stored) {
            return self::fromStored($stored, $language);
        }

        $verse = $dailyRhythm['verse'] ?? null;

        if ($verse instanceof BibleVerse) {
            return self::fromBibleVerse($verse, $language);
        }

        $affirmation = $dailyRhythm['affirmation'] ?? [];
        $fallback = DailySpiritualRhythm::fallbackScriptureForTheme((string) ($affirmation['theme'] ?? 'peace'));
        $localized = BibleTranslations::verseFromReference($fallback['reference'], $language);
        $english = BibleTranslations::verseFromReference($fallback['reference'], 'en');

        if ($localized || $english) {
            return self::fromResolvedVerse($localized ?: $english, $language);
        }

        return [
            'text' => $fallback['text'],
            'reference' => $fallback['reference'],
            'book_slug' => null,
            'chapter' => null,
            'language' => 'en',
            'version' => null,
            'is_localized' => $language === 'en',
            'reader_url' => null,
        ];
    }

    /**
     * @return array{text:string,reference:string,book_slug:string|null,chapter:string|null,language:string,version:string|null,is_localized:bool,reader_url:string|null}
     */
    private static function fromStored(DailyScripture $stored, string $language): array
    {
        $localized = BibleTranslations::verseFor(
            $stored->book,
            $stored->chapter,
            $stored->verse,
            $language,
            $stored->translation,
        );

        if ($localized) {
            return self::fromResolvedVerse($localized, $language);
        }

        $routeParameters = $stored->bibleRouteParameters();
        $version = $stored->translation ? strtoupper((string) $stored->translation) : null;

        return [
            'text' => $stored->text,
            'reference' => trim($stored->reference.' '.$version),
            'book_slug' => $routeParameters['book'] ?? null,
            'chapter' => $stored->chapter ? (string) $stored->chapter : null,
            'language' => 'en',
            'version' => $version,
            'is_localized' => $language === 'en',
            'reader_url' => isset($routeParameters['book'], $routeParameters['chapter'])
                ? BibleTranslations::readerUrl($routeParameters['book'], $routeParameters['chapter'], 'en', $version)
                : null,
        ];
    }

    /**
     * @return array{text:string,reference:string,book_slug:string|null,chapter:string|null,language:string,version:string|null,is_localized:bool,reader_url:string|null}
     */
    private static function fromBibleVerse(BibleVerse $source, string $language): array
    {
        return self::fromResolvedVerse(
            BibleTranslations::equivalentVerse($source, $language) ?: $source,
            $language,
        );
    }

    /**
     * @return array{text:string,reference:string,book_slug:string|null,chapter:string|null,language:string,version:string|null,is_localized:bool,reader_url:string|null}
     */
    private static function fromResolvedVerse(BibleVerse $verse, string $requestedLanguage): array
    {
        $verse->loadMissing('book');
        $language = $verse->language ?: 'en';

        return [
            'text' => $verse->text,
            'reference' => "{$verse->book->name} {$verse->chapter}:{$verse->verse} {$verse->version}",
            'book_slug' => $verse->book->slug,
            'chapter' => (string) $verse->chapter,
            'language' => $language,
            'version' => $verse->version,
            'is_localized' => $language === strtolower($requestedLanguage),
            'reader_url' => BibleTranslations::readerUrl($verse->book->slug, $verse->chapter, $language, $verse->version),
        ];
    }
}
