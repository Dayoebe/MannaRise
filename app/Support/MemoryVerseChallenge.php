<?php

namespace App\Support;

use App\Models\BibleVerse;
use Carbon\CarbonImmutable;
use Carbon\CarbonInterface;

class MemoryVerseChallenge
{
    private const START_DATE = '2024-01-01';

    private const VERSES = [
        [
            'book' => 'Joshua',
            'chapter' => 1,
            'verse' => 9,
            'reference' => 'Joshua 1:9 KJV',
            'text' => 'Have not I commanded thee? Be strong and of a good courage; be not afraid, neither be thou dismayed: for the LORD thy God is with thee whithersoever thou goest.',
        ],
        [
            'book' => 'Psalms',
            'chapter' => 119,
            'verse' => 105,
            'reference' => 'Psalm 119:105 KJV',
            'text' => 'Thy word is a lamp unto my feet, and a light unto my path.',
        ],
        [
            'book' => 'Philippians',
            'chapter' => 4,
            'verse' => 6,
            'reference' => 'Philippians 4:6 KJV',
            'text' => 'Be careful for nothing; but in every thing by prayer and supplication with thanksgiving let your requests be made known unto God.',
        ],
        [
            'book' => 'Isaiah',
            'chapter' => 26,
            'verse' => 3,
            'reference' => 'Isaiah 26:3 KJV',
            'text' => 'Thou wilt keep him in perfect peace, whose mind is stayed on thee: because he trusteth in thee.',
        ],
        [
            'book' => 'Romans',
            'chapter' => 8,
            'verse' => 28,
            'reference' => 'Romans 8:28 KJV',
            'text' => 'And we know that all things work together for good to them that love God, to them who are the called according to his purpose.',
        ],
        [
            'book' => 'Proverbs',
            'chapter' => 3,
            'verse' => 5,
            'reference' => 'Proverbs 3:5 KJV',
            'text' => 'Trust in the LORD with all thine heart; and lean not unto thine own understanding.',
        ],
        [
            'book' => 'Matthew',
            'chapter' => 11,
            'verse' => 28,
            'reference' => 'Matthew 11:28 KJV',
            'text' => 'Come unto me, all ye that labour and are heavy laden, and I will give you rest.',
        ],
        [
            'book' => 'James',
            'chapter' => 1,
            'verse' => 5,
            'reference' => 'James 1:5 KJV',
            'text' => 'If any of you lack wisdom, let him ask of God, that giveth to all men liberally, and upbraideth not; and it shall be given him.',
        ],
    ];

    /**
     * @return array{week_start: string, reference: string, text: string, bible_verse_id: int|null, book_slug: string|null, chapter: int|null}
     */
    public static function current(?CarbonInterface $date = null): array
    {
        $date = $date
            ? CarbonImmutable::instance($date)->startOfWeek()
            : CarbonImmutable::today()->startOfWeek();

        return self::forWeek($date);
    }

    /**
     * @return array{week_start: string, reference: string, text: string, bible_verse_id: int|null, book_slug: string|null, chapter: int|null}
     */
    public static function forWeek(CarbonInterface $weekStart): array
    {
        $weekStart = CarbonImmutable::instance($weekStart)->startOfWeek();
        $index = self::positiveModulo((int) CarbonImmutable::parse(self::START_DATE)->startOfWeek()->diffInWeeks($weekStart, false), count(self::VERSES));
        $definition = self::VERSES[$index];

        $verse = BibleVerse::query()
            ->with('book')
            ->where('version', 'KJV')
            ->where('chapter', $definition['chapter'])
            ->where('verse', $definition['verse'])
            ->whereHas('book', fn ($query) => $query->where('name', $definition['book']))
            ->first();

        return [
            'week_start' => $weekStart->toDateString(),
            'reference' => $verse
                ? "{$verse->book->name} {$verse->chapter}:{$verse->verse} KJV"
                : $definition['reference'],
            'text' => $verse?->text ?: $definition['text'],
            'bible_verse_id' => $verse?->id,
            'book_slug' => $verse?->book?->slug,
            'chapter' => $verse?->chapter,
        ];
    }

    private static function positiveModulo(int $value, int $divisor): int
    {
        return ($value % $divisor + $divisor) % $divisor;
    }
}
