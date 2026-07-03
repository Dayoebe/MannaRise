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
        $theme = (string) (($dailyRhythm['affirmation'] ?? [])['theme'] ?? 'peace');
        $stored = DailyScripture::query()
            ->active()
            ->whereDate('verse_date', $date->toDateString())
            ->first();

        if ($stored) {
            $scripture = self::fromStored($stored, $language);

            return $scripture['is_localized']
                ? $scripture
                : (self::localizedFallback($stored->reference, $theme, $language) ?? $scripture);
        }

        $verse = $dailyRhythm['verse'] ?? null;

        if ($verse instanceof BibleVerse) {
            $scripture = self::fromBibleVerse($verse, $language);

            return $scripture['is_localized']
                ? $scripture
                : (self::localizedFallback($scripture['reference'], $theme, $language) ?? $scripture);
        }

        $fallback = DailySpiritualRhythm::fallbackScriptureForTheme($theme);
        $localized = BibleTranslations::verseFromReference($fallback['reference'], $language);
        $english = BibleTranslations::verseFromReference($fallback['reference'], 'en');

        if ($localized) {
            return self::fromResolvedVerse($localized, $language);
        }

        $fallbackScripture = self::localizedFallback($fallback['reference'], $theme, $language);

        if ($fallbackScripture) {
            return $fallbackScripture;
        }

        if ($english) {
            return self::fromResolvedVerse($english, $language);
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
     * @return array{text:string,reference:string,book_slug:string|null,chapter:string|null,language:string,version:string|null,is_localized:bool,reader_url:string|null}|null
     */
    private static function localizedFallback(?string $reference, string $theme, string $language): ?array
    {
        $scripture = $reference
            ? LocalizedDailyContent::scriptureForReference($reference, $language)
            : null;
        $scripture ??= LocalizedDailyContent::scriptureForTheme($theme, $language);

        if (! $scripture) {
            return null;
        }

        return [
            'text' => $scripture['text'],
            'reference' => $scripture['reference'],
            'book_slug' => $scripture['book_slug'],
            'chapter' => $scripture['chapter'] ? (string) $scripture['chapter'] : null,
            'language' => $scripture['language'],
            'version' => $scripture['version'],
            'is_localized' => true,
            'reader_url' => $scripture['book_slug'] && $scripture['chapter']
                ? BibleTranslations::readerUrl($scripture['book_slug'], $scripture['chapter'], $language)
                : null,
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
