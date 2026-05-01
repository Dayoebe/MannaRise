<?php

namespace Database\Seeders;

use App\Models\BibleBook;
use App\Models\BibleVerse;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;
use Throwable;

class BibleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $books = $this->seedBooks();

        if ($this->hasCompleteCleanKjvImport()) {
            return;
        }

        $verses = $this->loadVersesFromLocalFile()
            ?? $this->loadVersesFromRemoteSource()
            ?? $this->fallbackVerses();

        $this->importVerses($verses, $books);
    }

    private function seedBooks()
    {
        return collect([
            ['Genesis', 'Gen', 'Old Testament'], ['Exodus', 'Exod', 'Old Testament'], ['Leviticus', 'Lev', 'Old Testament'], ['Numbers', 'Num', 'Old Testament'], ['Deuteronomy', 'Deut', 'Old Testament'],
            ['Joshua', 'Josh', 'Old Testament'], ['Judges', 'Judg', 'Old Testament'], ['Ruth', 'Ruth', 'Old Testament'], ['1 Samuel', '1 Sam', 'Old Testament'], ['2 Samuel', '2 Sam', 'Old Testament'],
            ['1 Kings', '1 Kgs', 'Old Testament'], ['2 Kings', '2 Kgs', 'Old Testament'], ['1 Chronicles', '1 Chr', 'Old Testament'], ['2 Chronicles', '2 Chr', 'Old Testament'], ['Ezra', 'Ezra', 'Old Testament'],
            ['Nehemiah', 'Neh', 'Old Testament'], ['Esther', 'Esth', 'Old Testament'], ['Job', 'Job', 'Old Testament'], ['Psalms', 'Ps', 'Old Testament'], ['Proverbs', 'Prov', 'Old Testament'],
            ['Ecclesiastes', 'Eccl', 'Old Testament'], ['Song of Solomon', 'Song', 'Old Testament'], ['Isaiah', 'Isa', 'Old Testament'], ['Jeremiah', 'Jer', 'Old Testament'], ['Lamentations', 'Lam', 'Old Testament'],
            ['Ezekiel', 'Ezek', 'Old Testament'], ['Daniel', 'Dan', 'Old Testament'], ['Hosea', 'Hos', 'Old Testament'], ['Joel', 'Joel', 'Old Testament'], ['Amos', 'Amos', 'Old Testament'],
            ['Obadiah', 'Obad', 'Old Testament'], ['Jonah', 'Jonah', 'Old Testament'], ['Micah', 'Mic', 'Old Testament'], ['Nahum', 'Nah', 'Old Testament'], ['Habakkuk', 'Hab', 'Old Testament'],
            ['Zephaniah', 'Zeph', 'Old Testament'], ['Haggai', 'Hag', 'Old Testament'], ['Zechariah', 'Zech', 'Old Testament'], ['Malachi', 'Mal', 'Old Testament'],
            ['Matthew', 'Matt', 'New Testament'], ['Mark', 'Mark', 'New Testament'], ['Luke', 'Luke', 'New Testament'], ['John', 'John', 'New Testament'], ['Acts', 'Acts', 'New Testament'],
            ['Romans', 'Rom', 'New Testament'], ['1 Corinthians', '1 Cor', 'New Testament'], ['2 Corinthians', '2 Cor', 'New Testament'], ['Galatians', 'Gal', 'New Testament'], ['Ephesians', 'Eph', 'New Testament'],
            ['Philippians', 'Phil', 'New Testament'], ['Colossians', 'Col', 'New Testament'], ['1 Thessalonians', '1 Thess', 'New Testament'], ['2 Thessalonians', '2 Thess', 'New Testament'], ['1 Timothy', '1 Tim', 'New Testament'],
            ['2 Timothy', '2 Tim', 'New Testament'], ['Titus', 'Titus', 'New Testament'], ['Philemon', 'Phlm', 'New Testament'], ['Hebrews', 'Heb', 'New Testament'], ['James', 'Jas', 'New Testament'],
            ['1 Peter', '1 Pet', 'New Testament'], ['2 Peter', '2 Pet', 'New Testament'], ['1 John', '1 John', 'New Testament'], ['2 John', '2 John', 'New Testament'], ['3 John', '3 John', 'New Testament'],
            ['Jude', 'Jude', 'New Testament'], ['Revelation', 'Rev', 'New Testament'],
        ])->map(function (array $book, int $index) {
            [$name, $abbreviation, $testament] = $book;

            return BibleBook::updateOrCreate(
                ['slug' => Str::slug($name)],
                [
                    'book_order' => $index + 1,
                    'name' => $name,
                    'slug' => Str::slug($name),
                    'abbreviation' => $abbreviation,
                    'testament' => $testament,
                    'chapters' => 1,
                ],
            );
        })->keyBy('name');
    }

    private function hasCompleteCleanKjvImport(): bool
    {
        return BibleVerse::where('language', 'en')->where('version', 'KJV')->count() >= 31102
            && ! BibleVerse::where('language', 'en')->where('version', 'KJV')->where('text', 'like', '#%')->exists();
    }

    private function loadVersesFromLocalFile(): ?array
    {
        $paths = [
            database_path('seeders/data/kjv-verses.json'),
            database_path('seeders/data/verses-1769.json'),
            storage_path('app/private/kjv-verses.json'),
            storage_path('app/kjv-verses.json'),
        ];

        foreach ($paths as $path) {
            if (! File::exists($path)) {
                continue;
            }

            $content = File::get($path);
            $decoded = json_decode($content, true);

            if (json_last_error() === JSON_ERROR_NONE && is_array($decoded) && $decoded !== []) {
                $this->command?->info('KJV Bible import loaded from local file: '.$path);

                return $decoded;
            }
        }

        return null;
    }

    private function loadVersesFromRemoteSource(): ?array
    {
        $url = 'https://raw.githubusercontent.com/farskipper/kjv/master/json/verses-1769.json';

        try {
            $response = Http::timeout(60)->get($url);

            if ($response->successful() && is_array($response->json())) {
                $this->command?->info('KJV Bible import loaded from remote source.');

                return $response->json();
            }
        } catch (Throwable $exception) {
            $this->command?->warn('Remote KJV download failed. Falling back to bundled starter verses.');
        }

        return null;
    }

    private function fallbackVerses(): array
    {
        $this->command?->warn('Using offline-safe starter Bible verses. Add a local KJV JSON file for full offline import.');

        return [
            'Genesis 1:1' => 'In the beginning God created the heaven and the earth.',
            'Genesis 1:2' => 'And the earth was without form, and void; and darkness was upon the face of the deep. And the Spirit of God moved upon the face of the waters.',
            'Genesis 1:3' => 'And God said, Let there be light: and there was light.',
            'Psalms 23:1' => 'The LORD is my shepherd; I shall not want.',
            'Psalms 23:2' => 'He maketh me to lie down in green pastures: he leadeth me beside the still waters.',
            'Psalms 23:3' => 'He restoreth my soul: he leadeth me in the paths of righteousness for his name\'s sake.',
            'Psalms 23:4' => 'Yea, though I walk through the valley of the shadow of death, I will fear no evil: for thou art with me; thy rod and thy staff they comfort me.',
            'John 1:1' => 'In the beginning was the Word, and the Word was with God, and the Word was God.',
            'John 1:2' => 'The same was in the beginning with God.',
            'John 1:3' => 'All things were made by him; and without him was not any thing made that was made.',
            'John 3:16' => 'For God so loved the world, that he gave his only begotten Son, that whosoever believeth in him should not perish, but have everlasting life.',
            'Romans 8:28' => 'And we know that all things work together for good to them that love God, to them who are the called according to his purpose.',
            'Philippians 4:6' => 'Be careful for nothing; but in every thing by prayer and supplication with thanksgiving let your requests be made known unto God.',
            'Philippians 4:7' => 'And the peace of God, which passeth all understanding, shall keep your hearts and minds through Christ Jesus.',
        ];
    }

    private function importVerses(array $verses, $books): void
    {
        $sourceBookAliases = [
            "Solomon's Song" => 'Song of Solomon',
            'Psalm' => 'Psalms',
        ];

        $chapterCounts = [];
        $rows = [];
        $now = now();

        foreach ($verses as $reference => $text) {
            if (is_array($text)) {
                $reference = $text['reference'] ?? null;
                $text = $text['text'] ?? null;
            }

            if (! is_string($reference) || ! is_string($text)) {
                continue;
            }

            if (! preg_match('/^(.+) (\d+):(\d+)$/', $reference, $matches)) {
                continue;
            }

            [, $bookName, $chapter, $verse] = $matches;
            $bookName = $sourceBookAliases[$bookName] ?? $bookName;
            $book = $books[$bookName] ?? null;

            if (! $book) {
                continue;
            }

            $chapter = (int) $chapter;
            $verse = (int) $verse;
            $chapterCounts[$bookName] = max($chapterCounts[$bookName] ?? 1, $chapter);

            $rows[] = [
                'bible_book_id' => $book->id,
                'language' => 'en',
                'version' => 'KJV',
                'chapter' => $chapter,
                'verse' => $verse,
                'text' => trim(str_replace(['[', ']', '#'], '', $text)),
                'created_at' => $now,
                'updated_at' => $now,
            ];

            if (count($rows) >= 1000) {
                BibleVerse::upsert($rows, ['language', 'version', 'bible_book_id', 'chapter', 'verse'], ['text', 'updated_at']);
                $rows = [];
            }
        }

        if ($rows !== []) {
            BibleVerse::upsert($rows, ['language', 'version', 'bible_book_id', 'chapter', 'verse'], ['text', 'updated_at']);
        }

        foreach ($chapterCounts as $bookName => $chapters) {
            $books[$bookName]->update(['chapters' => $chapters]);
        }
    }
}
