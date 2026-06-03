<?php

namespace App\Support;

use App\Models\BibleBook;
use App\Models\BibleChapterCompletion;
use App\Models\BibleVerse;
use App\Models\User;
use Carbon\CarbonImmutable;
use Carbon\CarbonInterface;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;

class DailySpiritualRhythm
{
    private const START_DATE = '2024-01-01';

    private const AFFIRMATIONS = [
        [
            'text' => 'I receive God\'s wisdom for the decisions in front of me.',
            'reference' => 'James 1:5',
            'theme' => 'wisdom',
            'theme_label' => 'Wisdom',
            'terms' => ['wisdom', 'understanding', 'light', 'path', 'teach'],
            'verse_references' => ['James 1:5', 'Proverbs 3:5', 'Proverbs 3:6', 'Psalm 119:105', 'Isaiah 30:21'],
        ],
        [
            'text' => 'I am led by the peace of Christ, not by fear or hurry.',
            'reference' => 'Colossians 3:15',
            'theme' => 'peace',
            'theme_label' => 'Peace',
            'terms' => ['peace', 'rest', 'still', 'comfort', 'safe'],
            'verse_references' => ['Colossians 3:15', 'Philippians 4:6', 'Philippians 4:7', 'John 14:27', 'Isaiah 26:3', 'Psalm 23:2'],
        ],
        [
            'text' => 'God is faithful to strengthen me for today\'s assignment.',
            'reference' => '2 Thessalonians 3:3',
            'theme' => 'strength',
            'theme_label' => 'Strength',
            'terms' => ['strength', 'strong', 'help', 'establish', 'faithful'],
            'verse_references' => ['2 Thessalonians 3:3', 'Isaiah 41:10', 'Psalm 46:1', 'Ephesians 6:10', '1 Peter 5:10', 'Philippians 4:13'],
        ],
        [
            'text' => 'I walk in love, patience, and self-control through the Holy Spirit.',
            'reference' => 'Galatians 5:22-23',
            'theme' => 'fruit',
            'theme_label' => 'Fruit of the Spirit',
            'terms' => ['love', 'patience', 'gentle', 'kindness', 'spirit'],
            'verse_references' => ['Galatians 5:22', 'Galatians 5:23', 'Colossians 3:12', '1 Corinthians 13:4', '1 John 4:7', 'Colossians 3:14'],
        ],
        [
            'text' => 'The Lord renews my strength as I wait on Him.',
            'reference' => 'Isaiah 40:31',
            'theme' => 'renewal',
            'theme_label' => 'Renewal',
            'terms' => ['renew', 'restore', 'rest', 'wait', 'strength'],
            'verse_references' => ['Isaiah 40:31', 'Psalm 23:3', '2 Corinthians 4:16', 'Matthew 11:28', 'Psalm 27:14', 'Psalm 103:5'],
        ],
        [
            'text' => 'My heart is guarded by God\'s peace as I bring Him every concern.',
            'reference' => 'Philippians 4:6-7',
            'theme' => 'anxiety',
            'theme_label' => 'Guarded peace',
            'terms' => ['peace', 'care', 'heart', 'mind', 'thanksgiving'],
            'verse_references' => ['Philippians 4:6', 'Philippians 4:7', '1 Peter 5:7', 'Psalm 55:22', 'John 14:27'],
        ],
        [
            'text' => 'I am God\'s workmanship, prepared for good works in Christ.',
            'reference' => 'Ephesians 2:10',
            'theme' => 'purpose',
            'theme_label' => 'Purpose',
            'terms' => ['purpose', 'work', 'called', 'good', 'created'],
            'verse_references' => ['Ephesians 2:10', 'Jeremiah 29:11', 'Romans 8:28', 'Colossians 3:23', 'Matthew 5:16'],
        ],
        [
            'text' => 'God\'s word gives light for my next step.',
            'reference' => 'Psalm 119:105',
            'theme' => 'word',
            'theme_label' => 'God\'s word',
            'terms' => ['word', 'light', 'lamp', 'path', 'scripture'],
            'verse_references' => ['Psalm 119:105', 'Joshua 1:8', '2 Timothy 3:16', 'Hebrews 4:12', 'Proverbs 6:23'],
        ],
        [
            'text' => 'I can be steadfast because my labor in the Lord is not wasted.',
            'reference' => '1 Corinthians 15:58',
            'theme' => 'steadfast',
            'theme_label' => 'Steadfastness',
            'terms' => ['steadfast', 'labor', 'weary', 'work', 'reward'],
            'verse_references' => ['1 Corinthians 15:58', 'Galatians 6:9', 'Hebrews 6:10', 'Psalm 90:17', 'Colossians 3:23'],
        ],
        [
            'text' => 'The mercy of God is new over my life today.',
            'reference' => 'Lamentations 3:22-23',
            'theme' => 'mercy',
            'theme_label' => 'Mercy',
            'terms' => ['mercy', 'compassion', 'faithful', 'grace', 'restore'],
            'verse_references' => ['Lamentations 3:22', 'Lamentations 3:23', 'Psalm 103:8', '2 Corinthians 1:3', 'Hebrews 4:16', 'Psalm 23:3'],
        ],
        [
            'text' => 'I choose courage because the Lord is with me wherever I go.',
            'reference' => 'Joshua 1:9',
            'theme' => 'courage',
            'theme_label' => 'Courage',
            'terms' => ['courage', 'strong', 'fear', 'with thee', 'bold'],
            'verse_references' => ['Joshua 1:9', 'Deuteronomy 31:6', 'Psalm 27:1', '2 Timothy 1:7', 'Isaiah 41:10'],
        ],
        [
            'text' => 'Christ gives me strength for obedience, service, and endurance.',
            'reference' => 'Philippians 4:13',
            'theme' => 'endurance',
            'theme_label' => 'Endurance',
            'terms' => ['strength', 'grace', 'power', 'endure', 'help'],
            'verse_references' => ['Philippians 4:13', 'Isaiah 40:29', '2 Corinthians 12:9', 'Ephesians 6:10', 'Psalm 18:32'],
        ],
        [
            'text' => 'I am planted in God\'s love and growing in spiritual maturity.',
            'reference' => 'Ephesians 3:17-19',
            'theme' => 'growth',
            'theme_label' => 'Spiritual growth',
            'terms' => ['rooted', 'love', 'grow', 'fruit', 'abide'],
            'verse_references' => ['Ephesians 3:17', 'Ephesians 3:18', 'Ephesians 3:19', 'Colossians 2:7', 'John 15:5', 'Psalm 1:3'],
        ],
        [
            'text' => 'The Lord is my shepherd; I have what I need for today.',
            'reference' => 'Psalm 23:1',
            'theme' => 'provision',
            'theme_label' => 'Provision',
            'terms' => ['shepherd', 'need', 'provide', 'seek', 'good'],
            'verse_references' => ['Psalm 23:1', 'Matthew 6:33', 'Philippians 4:19', 'John 10:11', 'Psalm 34:10'],
        ],
    ];

    private const UPLIFTING_FALLBACK_REFERENCES = [
        'Psalm 23:1',
        'Psalm 23:3',
        'John 3:16',
        'Romans 8:28',
        'Philippians 4:6',
        'Philippians 4:7',
        'Joshua 1:9',
        'Isaiah 40:31',
        'Psalm 119:105',
        'Lamentations 3:22',
        'Ephesians 2:10',
        'Philippians 4:13',
    ];

    public static function forDate(?CarbonInterface $date = null): array
    {
        $date = self::normalizeDate($date);
        $affirmation = self::affirmationForDate($date);

        return [
            'date' => $date,
            'verse' => self::verseOfTheDay($date, $affirmation),
            'affirmation' => $affirmation,
            'challenge' => self::readingPlanForDate($date),
        ];
    }

    public static function affirmationForDate(?CarbonInterface $date = null): array
    {
        $date = self::normalizeDate($date);
        $dayNumber = self::dayNumber($date);

        return self::AFFIRMATIONS[self::positiveModulo($dayNumber, count(self::AFFIRMATIONS))];
    }

    public static function verseForDate(?CarbonInterface $date = null): ?BibleVerse
    {
        $date = self::normalizeDate($date);

        return self::verseOfTheDay($date, self::affirmationForDate($date));
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

    private static function verseOfTheDay(CarbonImmutable $date, array $affirmation): ?BibleVerse
    {
        $themedVerses = self::versesForReferences($affirmation['verse_references'] ?? []);

        if ($themedVerses->isNotEmpty()) {
            return self::stableVerseFrom($themedVerses, $date, $affirmation['theme'] ?? $affirmation['reference']);
        }

        $termMatchedVerse = self::verseMatchingTerms($date, $affirmation['terms'] ?? [], $affirmation['theme'] ?? 'daily');

        if ($termMatchedVerse) {
            return $termMatchedVerse;
        }

        $fallbackVerses = self::versesForReferences(self::UPLIFTING_FALLBACK_REFERENCES);

        if ($fallbackVerses->isNotEmpty()) {
            return self::stableVerseFrom($fallbackVerses, $date, 'uplifting-fallback');
        }

        return self::anyVerseForDate($date);
    }

    private static function versesForReferences(array $references): Collection
    {
        return collect($references)
            ->map(fn (string $reference): ?BibleVerse => self::findVerseByReference($reference))
            ->filter()
            ->unique('id')
            ->values();
    }

    private static function findVerseByReference(string $reference): ?BibleVerse
    {
        $parsed = self::parseVerseReference($reference);

        if (! $parsed) {
            return null;
        }

        $book = BibleBook::query()
            ->where('slug', $parsed['book_slug'])
            ->first(['id']);

        if (! $book) {
            return null;
        }

        return BibleVerse::query()
            ->with('book')
            ->where('version', 'KJV')
            ->where('bible_book_id', $book->id)
            ->where('chapter', $parsed['chapter'])
            ->where('verse', $parsed['verse'])
            ->first();
    }

    private static function parseVerseReference(string $reference): ?array
    {
        if (! preg_match('/^(.+?)\s+(\d+):(\d+)(?:-\d+)?$/', trim($reference), $matches)) {
            return null;
        }

        $bookName = trim($matches[1]);
        $bookName = match (Str::lower($bookName)) {
            'psalm' => 'Psalms',
            default => $bookName,
        };

        return [
            'book_slug' => Str::slug($bookName),
            'chapter' => (int) $matches[2],
            'verse' => (int) $matches[3],
        ];
    }

    private static function verseMatchingTerms(CarbonImmutable $date, array $terms, string $theme): ?BibleVerse
    {
        $terms = collect($terms)
            ->map(fn (string $term): string => trim($term))
            ->filter()
            ->values();

        if ($terms->isEmpty()) {
            return null;
        }

        $query = BibleVerse::query()
            ->with('book')
            ->where('version', 'KJV')
            ->where(function ($query) use ($terms): void {
                foreach ($terms as $term) {
                    $query->orWhere('text', 'like', '%'.$term.'%');
                }
            });

        $count = (clone $query)->count();

        if ($count === 0) {
            return null;
        }

        return $query
            ->orderBy('id')
            ->offset(self::stableIndex($date, $theme.'-terms', $count))
            ->limit(1)
            ->first();
    }

    private static function stableVerseFrom(Collection $verses, CarbonImmutable $date, string $salt): ?BibleVerse
    {
        if ($verses->isEmpty()) {
            return null;
        }

        return $verses->values()[self::stableIndex($date, $salt, $verses->count())] ?? null;
    }

    private static function stableIndex(CarbonImmutable $date, string $salt, int $count): int
    {
        return self::positiveModulo((int) crc32($date->toDateString().'|'.$salt), max(1, $count));
    }

    private static function anyVerseForDate(CarbonImmutable $date): ?BibleVerse
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
