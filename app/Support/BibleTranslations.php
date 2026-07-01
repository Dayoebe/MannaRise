<?php

namespace App\Support;

use App\Models\BibleBook;
use App\Models\BibleVerse;
use Illuminate\Support\Str;

class BibleTranslations
{
    private const PREFERRED_VERSIONS = [
        'en' => ['KJV', 'WEB', 'BBE', 'WEBBE'],
        'es' => ['RV1909'],
        'fr' => ['OSTV'],
        'pt' => ['ALMEIDA'],
        'sw' => ['SWA'],
    ];

    public static function equivalentVerse(BibleVerse $source, string $language): ?BibleVerse
    {
        $source->loadMissing('book');

        return self::verseFor(
            $source->book,
            $source->chapter,
            $source->verse,
            $language,
            $source->version,
        );
    }

    public static function verseFor(BibleBook|string|null $book, int|string|null $chapter, int|string|null $verse, string $language, ?string $sourceVersion = null): ?BibleVerse
    {
        $book = $book instanceof BibleBook ? $book : self::book($book);
        $chapter = (int) $chapter;
        $verse = self::firstVerseNumber($verse);
        $language = self::normalizeLanguage($language);

        if (! $book || $chapter < 1 || ! $verse) {
            return null;
        }

        $baseQuery = BibleVerse::query()
            ->with('book')
            ->where('language', $language)
            ->where('bible_book_id', $book->id)
            ->where('chapter', $chapter)
            ->where('verse', $verse);

        foreach (self::versionPreference($language, $sourceVersion) as $version) {
            $match = (clone $baseQuery)->where('version', $version)->first();

            if ($match) {
                return $match;
            }
        }

        return $baseQuery->orderBy('version')->first();
    }

    public static function verseFromReference(string $reference, string $language): ?BibleVerse
    {
        $parsed = self::parseReference($reference);

        if (! $parsed) {
            return null;
        }

        return self::verseFor($parsed['book'], $parsed['chapter'], $parsed['verse'], $language, $parsed['version']);
    }

    public static function readerUrl(?string $bookSlug = null, int|string|null $chapter = null, string $language = 'en', ?string $version = null): ?string
    {
        $parameters = [];

        if ($bookSlug && $chapter) {
            $parameters['book'] = $bookSlug;
            $parameters['chapter'] = (int) $chapter;
        }

        $language = self::normalizeLanguage($language);
        $version ??= self::preferredAvailableVersion($language);

        if ($version) {
            $parameters['language'] = $language;
            $parameters['version'] = $version;
        }

        return route('bible', $parameters);
    }

    public static function preferredAvailableVersion(string $language): ?string
    {
        $language = self::normalizeLanguage($language);
        $baseQuery = BibleVerse::query()->where('language', $language);

        foreach (self::versionPreference($language) as $version) {
            if ((clone $baseQuery)->where('version', $version)->exists()) {
                return $version;
            }
        }

        return (clone $baseQuery)->orderBy('version')->value('version');
    }

    /**
     * @return array<int, string>
     */
    private static function versionPreference(string $language, ?string $sourceVersion = null): array
    {
        $versions = self::PREFERRED_VERSIONS[$language] ?? [];

        if ($language === 'en' && $sourceVersion) {
            array_unshift($versions, strtoupper($sourceVersion));
        }

        return array_values(array_unique(array_filter(array_map(
            fn (string $version): string => strtoupper(trim($version)),
            $versions,
        ))));
    }

    private static function book(BibleBook|string|null $book): ?BibleBook
    {
        if (! is_string($book) || trim($book) === '') {
            return null;
        }

        return BibleBook::query()
            ->where('name', $book)
            ->orWhere('slug', Str::slug($book))
            ->first();
    }

    /**
     * @return array{book:string,chapter:int,verse:int,version:string|null}|null
     */
    private static function parseReference(string $reference): ?array
    {
        $reference = trim(preg_replace('/\s+/u', ' ', $reference) ?? '');

        if (! preg_match('/^(.+?)\s+(\d+):(\d+)(?:-\d+)?(?:\s+([A-Z0-9]+))?$/i', $reference, $matches)) {
            return null;
        }

        $book = match (Str::lower(trim($matches[1]))) {
            'psalm' => 'Psalms',
            default => trim($matches[1]),
        };

        return [
            'book' => $book,
            'chapter' => (int) $matches[2],
            'verse' => (int) $matches[3],
            'version' => isset($matches[4]) ? strtoupper($matches[4]) : null,
        ];
    }

    private static function firstVerseNumber(int|string|null $verse): ?int
    {
        if (is_int($verse)) {
            return $verse > 0 ? $verse : null;
        }

        if (! is_string($verse) || ! preg_match('/\d+/', $verse, $matches)) {
            return null;
        }

        $verse = (int) $matches[0];

        return $verse > 0 ? $verse : null;
    }

    private static function normalizeLanguage(string $language): string
    {
        return strtolower(Str::before(trim($language), '-')) ?: 'en';
    }
}
