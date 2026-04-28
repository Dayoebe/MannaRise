<?php

namespace App\Support;

use App\Models\BibleBook;
use App\Models\BibleChapterCompletion;
use App\Models\BibleVerse;
use App\Models\User;
use Carbon\CarbonImmutable;
use Carbon\CarbonInterface;
use Illuminate\Support\Collection;

class DailySpiritualRhythm
{
    private const START_DATE = '2024-01-01';

    private const AFFIRMATIONS = [
        [
            'text' => 'I receive God\'s wisdom for the decisions in front of me.',
            'reference' => 'James 1:5',
        ],
        [
            'text' => 'I am led by the peace of Christ, not by fear or hurry.',
            'reference' => 'Colossians 3:15',
        ],
        [
            'text' => 'God is faithful to strengthen me for today\'s assignment.',
            'reference' => '2 Thessalonians 3:3',
        ],
        [
            'text' => 'I walk in love, patience, and self-control through the Holy Spirit.',
            'reference' => 'Galatians 5:22-23',
        ],
        [
            'text' => 'The Lord renews my strength as I wait on Him.',
            'reference' => 'Isaiah 40:31',
        ],
        [
            'text' => 'My heart is guarded by God\'s peace as I bring Him every concern.',
            'reference' => 'Philippians 4:6-7',
        ],
        [
            'text' => 'I am God\'s workmanship, prepared for good works in Christ.',
            'reference' => 'Ephesians 2:10',
        ],
        [
            'text' => 'God\'s word gives light for my next step.',
            'reference' => 'Psalm 119:105',
        ],
        [
            'text' => 'I can be steadfast because my labor in the Lord is not wasted.',
            'reference' => '1 Corinthians 15:58',
        ],
        [
            'text' => 'The mercy of God is new over my life today.',
            'reference' => 'Lamentations 3:22-23',
        ],
        [
            'text' => 'I choose courage because the Lord is with me wherever I go.',
            'reference' => 'Joshua 1:9',
        ],
        [
            'text' => 'Christ gives me strength for obedience, service, and endurance.',
            'reference' => 'Philippians 4:13',
        ],
        [
            'text' => 'I am planted in God\'s love and growing in spiritual maturity.',
            'reference' => 'Ephesians 3:17-19',
        ],
        [
            'text' => 'The Lord is my shepherd; I have what I need for today.',
            'reference' => 'Psalm 23:1',
        ],
    ];

    public static function forDate(?CarbonInterface $date = null): array
    {
        $date = self::normalizeDate($date);
        $dayNumber = self::dayNumber($date);

        return [
            'date' => $date,
            'verse' => self::verseOfTheDay($date),
            'affirmation' => self::AFFIRMATIONS[self::positiveModulo($dayNumber, count(self::AFFIRMATIONS))],
            'challenge' => self::readingPlanForDate($date),
        ];
    }

    public static function challengePreview(?CarbonInterface $date = null, int $days = 7): Collection
    {
        $date = self::normalizeDate($date);

        return collect(range(0, max(0, $days - 1)))
            ->map(fn (int $offset) => self::readingPlanForDate($date->addDays($offset)))
            ->filter()
            ->values();
    }

    public static function catchUpPlanForUser(User $user, ?CarbonInterface $date = null): ?array
    {
        $date = self::normalizeDate($date);
        $sequence = self::chapterSequence();

        if ($sequence->isEmpty()) {
            return null;
        }

        $normalPlan = self::readingPlanForDate($date);
        $totalChapters = $sequence->count();
        $daysInYear = (int) $date->endOfYear()->dayOfYear;
        $expectedCompleted = (int) floor($date->dayOfYear * $totalChapters / $daysInYear);
        $completedKeys = $user->bibleChapterCompletions()
            ->get(['bible_book_id', 'chapter'])
            ->mapWithKeys(fn (BibleChapterCompletion $completion) => [$completion->bible_book_id.':'.$completion->chapter => true]);

        $completedCount = $completedKeys->count();
        $missedCount = max(0, $expectedCompleted - $completedCount);
        $dailyCount = max(1, $normalPlan ? $normalPlan['readings']->count() : 3);
        $extraCount = $missedCount > 0 ? min(5, max(1, (int) ceil($missedCount / 7))) : 0;

        $readings = $sequence
            ->reject(fn (array $reading) => $completedKeys->has($reading['bible_book_id'].':'.$reading['chapter']))
            ->take($dailyCount + $extraCount)
            ->values();

        return [
            'date' => $date,
            'readings' => $readings,
            'reading_label' => self::formatReadingLabel($readings),
            'missed_count' => $missedCount,
            'completed_chapters' => $completedCount,
            'expected_chapters' => $expectedCompleted,
            'total_chapters' => $totalChapters,
            'progress_percent' => round(($completedCount / $totalChapters) * 100, 1),
            'is_catch_up' => $missedCount > 0,
            'extra_chapters' => $extraCount,
        ];
    }

    public static function completeReadingsForUser(User $user, iterable $readings, ?CarbonInterface $date = null): int
    {
        $date = self::normalizeDate($date);
        $completed = 0;

        foreach ($readings as $reading) {
            if (! isset($reading['bible_book_id'], $reading['chapter'])) {
                continue;
            }

            BibleChapterCompletion::firstOrCreate(
                [
                    'user_id' => $user->id,
                    'bible_book_id' => $reading['bible_book_id'],
                    'chapter' => $reading['chapter'],
                ],
                [
                    'assigned_on' => $date->toDateString(),
                    'source' => 'bible-in-a-year',
                    'completed_at' => now(),
                ],
            );

            $completed++;
        }

        return $completed;
    }

    public static function readingPlanForDate(?CarbonInterface $date = null): ?array
    {
        $date = self::normalizeDate($date);
        $books = BibleBook::query()
            ->orderBy('book_order')
            ->get(['id', 'name', 'slug', 'testament', 'chapters']);

        if ($books->isEmpty()) {
            return null;
        }

        $totalChapters = (int) $books->sum('chapters');

        if ($totalChapters === 0) {
            return null;
        }

        $daysInYear = (int) $date->endOfYear()->dayOfYear;
        $dayOfYear = min((int) $date->dayOfYear, $daysInYear);
        $startIndex = (int) floor(($dayOfYear - 1) * $totalChapters / $daysInYear);
        $endIndex = max($startIndex, (int) floor($dayOfYear * $totalChapters / $daysInYear) - 1);
        $readings = self::readingsBetween($books, $startIndex, $endIndex);
        $completedChapters = min($totalChapters, $endIndex + 1);

        return [
            'date' => $date,
            'day' => $dayOfYear,
            'days_in_year' => $daysInYear,
            'progress_percent' => round(($completedChapters / $totalChapters) * 100, 1),
            'completed_chapters' => $completedChapters,
            'total_chapters' => $totalChapters,
            'readings' => $readings,
            'reading_label' => self::formatReadingLabel($readings),
        ];
    }

    private static function verseOfTheDay(CarbonImmutable $date): ?BibleVerse
    {
        $count = BibleVerse::where('version', 'KJV')->count();

        if ($count === 0) {
            return null;
        }

        $offset = self::positiveModulo(self::dayNumber($date), $count);

        return BibleVerse::query()
            ->with('book')
            ->where('version', 'KJV')
            ->orderBy('id')
            ->offset($offset)
            ->limit(1)
            ->first();
    }

    public static function chapterSequence(): Collection
    {
        $books = BibleBook::query()
            ->orderBy('book_order')
            ->get(['id', 'name', 'slug', 'testament', 'chapters']);

        return self::readingsBetween($books, 0, max(0, (int) $books->sum('chapters') - 1));
    }

    private static function readingsBetween(Collection $books, int $startIndex, int $endIndex): Collection
    {
        $readings = collect();
        $index = 0;

        foreach ($books as $book) {
            for ($chapter = 1; $chapter <= $book->chapters; $chapter++) {
                if ($index > $endIndex) {
                    return $readings;
                }

                if ($index >= $startIndex) {
                    $readings->push([
                        'bible_book_id' => $book->id,
                        'book' => $book->name,
                        'slug' => $book->slug,
                        'testament' => $book->testament,
                        'chapter' => $chapter,
                    ]);
                }

                $index++;
            }
        }

        return $readings;
    }

    public static function formatReadingLabel(Collection $readings): string
    {
        if ($readings->isEmpty()) {
            return 'No reading assigned';
        }

        $ranges = [];

        foreach ($readings as $reading) {
            $lastIndex = count($ranges) - 1;

            if (
                $lastIndex >= 0
                && $ranges[$lastIndex]['book'] === $reading['book']
                && $ranges[$lastIndex]['end'] + 1 === $reading['chapter']
            ) {
                $ranges[$lastIndex]['end'] = $reading['chapter'];

                continue;
            }

            $ranges[] = [
                'book' => $reading['book'],
                'start' => $reading['chapter'],
                'end' => $reading['chapter'],
            ];
        }

        return collect($ranges)
            ->map(function (array $range): string {
                $chapters = $range['start'] === $range['end']
                    ? (string) $range['start']
                    : $range['start'].'-'.$range['end'];

                return $range['book'].' '.$chapters;
            })
            ->join(', ');
    }

    private static function normalizeDate(?CarbonInterface $date): CarbonImmutable
    {
        return $date
            ? CarbonImmutable::instance($date)->startOfDay()
            : CarbonImmutable::today();
    }

    private static function dayNumber(CarbonImmutable $date): int
    {
        $start = CarbonImmutable::parse(self::START_DATE, $date->timezone)->startOfDay();

        return (int) $start->diffInDays($date, false);
    }

    private static function positiveModulo(int $value, int $divisor): int
    {
        return ($value % $divisor + $divisor) % $divisor;
    }
}
