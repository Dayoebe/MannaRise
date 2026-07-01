<?php

namespace App\Console\Commands;

use App\Models\BibleBook;
use App\Models\BibleVerse;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Http;
use XMLReader;

class SyncPublicBibleTranslations extends Command
{
    protected $signature = 'bible:sync-public-translations
        {--only=* : Version code to import, for example WEB, BBE, RV1909}
        {--force : Re-import translations even when verses already exist}';

    protected $description = 'Download and import bundled public-domain Bible translations from open-bibles.';

    private const SOURCES = [
        'WEB' => [
            'language' => 'en',
            'format' => 'usfx',
            'url' => 'https://raw.githubusercontent.com/seven1m/open-bibles/master/eng-web.usfx.xml',
        ],
        'BBE' => [
            'language' => 'en',
            'format' => 'usfx',
            'url' => 'https://raw.githubusercontent.com/seven1m/open-bibles/master/eng-bbe.usfx.xml',
        ],
        'WEBBE' => [
            'language' => 'en',
            'format' => 'usfx',
            'url' => 'https://raw.githubusercontent.com/seven1m/open-bibles/master/eng-gb-webbe.usfx.xml',
        ],
        'RV1909' => [
            'language' => 'es',
            'format' => 'usfx',
            'url' => 'https://raw.githubusercontent.com/seven1m/open-bibles/master/spa-rv1909.usfx.xml',
        ],
        'OSTV' => [
            'language' => 'fr',
            'format' => 'osis',
            'url' => 'https://raw.githubusercontent.com/seven1m/open-bibles/master/fra-ostervald.osis.xml',
        ],
        'SWA' => [
            'language' => 'sw',
            'format' => 'osis',
            'url' => 'https://raw.githubusercontent.com/seven1m/open-bibles/master/swa-swahili.osis.xml',
        ],
        'ALMEIDA' => [
            'language' => 'pt',
            'format' => 'usfx',
            'url' => 'https://raw.githubusercontent.com/seven1m/open-bibles/master/por-almeida.usfx.xml',
        ],
    ];

    public function handle(): int
    {
        $books = BibleBook::query()->get()->keyBy('name');

        if ($books->isEmpty()) {
            $this->call('db:seed', ['--class' => 'BibleSeeder']);
            $books = BibleBook::query()->get()->keyBy('name');
        }

        if ($books->isEmpty()) {
            $this->error('Bible books are missing and could not be seeded.');

            return self::FAILURE;
        }

        $selected = collect((array) $this->option('only'))
            ->map(fn (string $version): string => strtoupper($version))
            ->filter()
            ->values();

        $sources = $selected->isEmpty()
            ? self::SOURCES
            : array_intersect_key(self::SOURCES, array_flip($selected->all()));

        if ($sources === []) {
            $this->error('No matching public Bible translation source was found.');

            return self::FAILURE;
        }

        $failed = false;

        foreach ($sources as $version => $source) {
            $language = $source['language'];

            if (! $this->option('force') && BibleVerse::where('language', $language)->where('version', $version)->count() > 1000) {
                $this->line("{$language} {$version} already has imported verses. Use --force to refresh it.");

                continue;
            }

            $path = $this->downloadSource($version, $source['url']);

            if (! $path) {
                $failed = true;

                continue;
            }

            $this->line("Importing {$language} {$version}...");

            $imported = $source['format'] === 'osis'
                ? $this->importOsis($path, $books, $language, $version)
                : $this->importUsfx($path, $books, $language, $version);

            $this->info("Imported {$imported} {$language} {$version} verses.");
        }

        return $failed ? self::FAILURE : self::SUCCESS;
    }

    private function downloadSource(string $version, string $url): ?string
    {
        $directory = storage_path('app/private/bible-translations');
        $path = $directory.'/'.$version.'.xml';

        if (File::exists($path) && File::size($path) > 1000) {
            return $path;
        }

        File::ensureDirectoryExists($directory);

        try {
            $response = Http::timeout(120)->retry(2, 1000)->get($url);
        } catch (\Throwable $exception) {
            $this->error("Download failed for {$version}: {$exception->getMessage()}");

            return null;
        }

        if (! $response->successful() || trim($response->body()) === '') {
            $this->error("Download failed for {$version}: HTTP {$response->status()}");

            return null;
        }

        File::put($path, $response->body());

        return $path;
    }

    private function importUsfx(string $path, $books, string $language, string $version): int
    {
        $reader = new XMLReader;
        $reader->open($path, null, LIBXML_NONET | LIBXML_NOERROR | LIBXML_NOWARNING);

        $bookMap = $this->bookMap();
        $book = null;
        $chapter = null;
        $verse = null;
        $text = '';
        $rows = [];
        $chapterCounts = [];
        $imported = 0;
        $skipDepth = null;

        $flush = function () use (&$book, &$chapter, &$verse, &$text, &$rows, &$chapterCounts, &$imported, $language, $version): void {
            if (! $book || ! $chapter || ! $verse) {
                $text = '';

                return;
            }

            $cleanText = $this->cleanVerseText($text);

            if ($cleanText === '') {
                $text = '';

                return;
            }

            $chapterCounts[$book->name] = max($chapterCounts[$book->name] ?? (int) $book->chapters, $chapter);
            $rows[] = [
                'bible_book_id' => $book->id,
                'language' => $language,
                'version' => $version,
                'chapter' => $chapter,
                'verse' => $verse,
                'text' => $cleanText,
                'created_at' => now(),
                'updated_at' => now(),
            ];

            if (count($rows) >= 1000) {
                $imported += count($rows);
                BibleVerse::upsert($rows, ['language', 'version', 'bible_book_id', 'chapter', 'verse'], ['text', 'updated_at']);
                $rows = [];
            }

            $text = '';
        };

        while ($reader->read()) {
            if ($skipDepth !== null) {
                if ($reader->nodeType === XMLReader::END_ELEMENT && $reader->depth === $skipDepth) {
                    $skipDepth = null;
                }

                continue;
            }

            if ($reader->nodeType === XMLReader::ELEMENT) {
                if (in_array($reader->localName, ['f', 'x'], true)) {
                    $skipDepth = $reader->isEmptyElement ? null : $reader->depth;

                    continue;
                }

                if ($reader->localName === 'book') {
                    $flush();
                    $bookName = $bookMap[strtoupper((string) $reader->getAttribute('id'))] ?? null;
                    $book = $bookName ? ($books[$bookName] ?? null) : null;
                    $chapter = null;
                    $verse = null;

                    continue;
                }

                if ($reader->localName === 'c') {
                    $flush();
                    $chapter = (int) $reader->getAttribute('id');
                    $verse = null;

                    continue;
                }

                if ($reader->localName === 'v') {
                    $flush();
                    $verse = (int) $reader->getAttribute('id');
                }
            }

            if (($reader->nodeType === XMLReader::TEXT || $reader->nodeType === XMLReader::CDATA) && $book && $chapter && $verse) {
                $text .= ' '.$reader->value;
            }
        }

        $flush();
        $reader->close();

        if ($rows !== []) {
            $imported += count($rows);
            BibleVerse::upsert($rows, ['language', 'version', 'bible_book_id', 'chapter', 'verse'], ['text', 'updated_at']);
        }

        $this->updateChapterCounts($books, $chapterCounts);

        return $imported;
    }

    private function importOsis(string $path, $books, string $language, string $version): int
    {
        $reader = new XMLReader;
        $reader->open($path, null, LIBXML_NONET | LIBXML_NOERROR | LIBXML_NOWARNING);

        $bookMap = $this->bookMap();
        $rows = [];
        $chapterCounts = [];
        $imported = 0;

        while ($reader->read()) {
            if ($reader->nodeType !== XMLReader::ELEMENT || $reader->localName !== 'verse') {
                continue;
            }

            $osisId = (string) $reader->getAttribute('osisID');

            if (! preg_match('/^([1-3]?[A-Za-z]+)\.(\d+)\.(\d+)$/', $osisId, $matches)) {
                continue;
            }

            [, $bookCode, $chapter, $verse] = $matches;
            $bookName = $bookMap[$bookCode] ?? $bookMap[strtoupper($bookCode)] ?? null;
            $book = $bookName ? ($books[$bookName] ?? null) : null;

            if (! $book) {
                continue;
            }

            $text = $this->cleanVerseText($reader->readString());

            if ($text === '') {
                continue;
            }

            $chapter = (int) $chapter;
            $verse = (int) $verse;
            $chapterCounts[$book->name] = max($chapterCounts[$book->name] ?? (int) $book->chapters, $chapter);
            $rows[] = [
                'bible_book_id' => $book->id,
                'language' => $language,
                'version' => $version,
                'chapter' => $chapter,
                'verse' => $verse,
                'text' => $text,
                'created_at' => now(),
                'updated_at' => now(),
            ];

            if (count($rows) >= 1000) {
                $imported += count($rows);
                BibleVerse::upsert($rows, ['language', 'version', 'bible_book_id', 'chapter', 'verse'], ['text', 'updated_at']);
                $rows = [];
            }
        }

        $reader->close();

        if ($rows !== []) {
            $imported += count($rows);
            BibleVerse::upsert($rows, ['language', 'version', 'bible_book_id', 'chapter', 'verse'], ['text', 'updated_at']);
        }

        $this->updateChapterCounts($books, $chapterCounts);

        return $imported;
    }

    private function cleanVerseText(string $text): string
    {
        return trim(preg_replace('/\s+/u', ' ', html_entity_decode(strip_tags($text), ENT_QUOTES | ENT_HTML5, 'UTF-8')) ?? '');
    }

    private function updateChapterCounts($books, array $chapterCounts): void
    {
        foreach ($chapterCounts as $bookName => $chapters) {
            if (isset($books[$bookName]) && $chapters > (int) $books[$bookName]->chapters) {
                $books[$bookName]->update(['chapters' => $chapters]);
            }
        }
    }

    private function bookMap(): array
    {
        return [
            'GEN' => 'Genesis', 'Gen' => 'Genesis',
            'EXO' => 'Exodus', 'Exod' => 'Exodus',
            'LEV' => 'Leviticus', 'Lev' => 'Leviticus',
            'NUM' => 'Numbers', 'Num' => 'Numbers',
            'DEU' => 'Deuteronomy', 'Deut' => 'Deuteronomy',
            'JOS' => 'Joshua', 'Josh' => 'Joshua',
            'JDG' => 'Judges', 'Judg' => 'Judges',
            'RUT' => 'Ruth', 'Ruth' => 'Ruth',
            '1SA' => '1 Samuel', '1Sam' => '1 Samuel',
            '2SA' => '2 Samuel', '2Sam' => '2 Samuel',
            '1KI' => '1 Kings', '1Kgs' => '1 Kings',
            '2KI' => '2 Kings', '2Kgs' => '2 Kings',
            '1CH' => '1 Chronicles', '1Chr' => '1 Chronicles',
            '2CH' => '2 Chronicles', '2Chr' => '2 Chronicles',
            'EZR' => 'Ezra', 'Ezra' => 'Ezra',
            'NEH' => 'Nehemiah', 'Neh' => 'Nehemiah',
            'EST' => 'Esther', 'Esth' => 'Esther',
            'JOB' => 'Job', 'Job' => 'Job',
            'PSA' => 'Psalms', 'PS' => 'Psalms', 'Ps' => 'Psalms',
            'PRO' => 'Proverbs', 'Prov' => 'Proverbs',
            'ECC' => 'Ecclesiastes', 'Eccl' => 'Ecclesiastes',
            'SNG' => 'Song of Solomon', 'Song' => 'Song of Solomon',
            'ISA' => 'Isaiah', 'Isa' => 'Isaiah',
            'JER' => 'Jeremiah', 'Jer' => 'Jeremiah',
            'LAM' => 'Lamentations', 'Lam' => 'Lamentations',
            'EZK' => 'Ezekiel', 'Ezek' => 'Ezekiel',
            'DAN' => 'Daniel', 'Dan' => 'Daniel',
            'HOS' => 'Hosea', 'Hos' => 'Hosea',
            'JOL' => 'Joel', 'Joel' => 'Joel',
            'AMO' => 'Amos', 'Amos' => 'Amos',
            'OBA' => 'Obadiah', 'Obad' => 'Obadiah',
            'JON' => 'Jonah', 'Jonah' => 'Jonah',
            'MIC' => 'Micah', 'Mic' => 'Micah',
            'NAM' => 'Nahum', 'Nah' => 'Nahum',
            'HAB' => 'Habakkuk', 'Hab' => 'Habakkuk',
            'ZEP' => 'Zephaniah', 'Zeph' => 'Zephaniah',
            'HAG' => 'Haggai', 'Hag' => 'Haggai',
            'ZEC' => 'Zechariah', 'Zech' => 'Zechariah',
            'MAL' => 'Malachi', 'Mal' => 'Malachi',
            'MAT' => 'Matthew', 'Matt' => 'Matthew',
            'MRK' => 'Mark', 'Mark' => 'Mark',
            'LUK' => 'Luke', 'Luke' => 'Luke',
            'JHN' => 'John', 'John' => 'John',
            'ACT' => 'Acts', 'Acts' => 'Acts',
            'ROM' => 'Romans', 'Rom' => 'Romans',
            '1CO' => '1 Corinthians', '1Cor' => '1 Corinthians',
            '2CO' => '2 Corinthians', '2Cor' => '2 Corinthians',
            'GAL' => 'Galatians', 'Gal' => 'Galatians',
            'EPH' => 'Ephesians', 'Eph' => 'Ephesians',
            'PHP' => 'Philippians', 'Phil' => 'Philippians',
            'COL' => 'Colossians', 'Col' => 'Colossians',
            '1TH' => '1 Thessalonians', '1Thess' => '1 Thessalonians',
            '2TH' => '2 Thessalonians', '2Thess' => '2 Thessalonians',
            '1TI' => '1 Timothy', '1Tim' => '1 Timothy',
            '2TI' => '2 Timothy', '2Tim' => '2 Timothy',
            'TIT' => 'Titus', 'Titus' => 'Titus',
            'PHM' => 'Philemon', 'Phlm' => 'Philemon',
            'HEB' => 'Hebrews', 'Heb' => 'Hebrews',
            'JAS' => 'James', 'Jas' => 'James',
            '1PE' => '1 Peter', '1Pet' => '1 Peter',
            '2PE' => '2 Peter', '2Pet' => '2 Peter',
            '1JN' => '1 John', '1John' => '1 John',
            '2JN' => '2 John', '2John' => '2 John',
            '3JN' => '3 John', '3John' => '3 John',
            'JUD' => 'Jude', 'Jude' => 'Jude',
            'REV' => 'Revelation', 'Rev' => 'Revelation',
        ];
    }
}
