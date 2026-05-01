<?php

namespace App\Console\Commands;

use App\Models\BibleBook;
use App\Models\BibleVerse;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class ImportBibleTranslation extends Command
{
    protected $signature = 'bible:import-translation
        {path : JSON file path. Supports {"John 3:16":"text"} or [{"reference":"John 3:16","text":"..."}]}
        {--version=KJV : Translation/version label, for example KJV, WEB, RVR}
        {--language=en : Language code, for example en, es, fr, yo}';

    protected $description = 'Import a legal Bible translation or language file into the Bible reader.';

    public function handle(): int
    {
        $path = (string) $this->argument('path');
        $version = strtoupper(trim((string) $this->option('version')));
        $language = strtolower(trim((string) $this->option('language')));

        if (! File::exists($path)) {
            $this->error("File not found: {$path}");

            return self::FAILURE;
        }

        if ($version === '' || $language === '') {
            $this->error('Both --version and --language are required.');

            return self::FAILURE;
        }

        $decoded = json_decode(File::get($path), true);

        if (! is_array($decoded)) {
            $this->error('The translation file must be valid JSON.');

            return self::FAILURE;
        }

        $books = BibleBook::query()->get()->keyBy('name');

        if ($books->isEmpty()) {
            $this->error('Bible books are missing. Run the BibleSeeder first.');

            return self::FAILURE;
        }

        $aliases = [
            "Solomon's Song" => 'Song of Solomon',
            'Psalm' => 'Psalms',
        ];
        $rows = [];
        $chapterCounts = [];
        $now = now();
        $imported = 0;

        foreach ($decoded as $reference => $text) {
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
            $bookName = $aliases[$bookName] ?? $bookName;
            $book = $books[$bookName] ?? null;

            if (! $book) {
                continue;
            }

            $chapter = (int) $chapter;
            $verse = (int) $verse;
            $chapterCounts[$bookName] = max($chapterCounts[$bookName] ?? (int) $book->chapters, $chapter);
            $rows[] = [
                'bible_book_id' => $book->id,
                'language' => $language,
                'version' => $version,
                'chapter' => $chapter,
                'verse' => $verse,
                'text' => trim($text),
                'created_at' => $now,
                'updated_at' => $now,
            ];

            if (count($rows) >= 1000) {
                $imported += count($rows);
                BibleVerse::upsert($rows, ['language', 'version', 'bible_book_id', 'chapter', 'verse'], ['text', 'updated_at']);
                $rows = [];
            }
        }

        if ($rows !== []) {
            $imported += count($rows);
            BibleVerse::upsert($rows, ['language', 'version', 'bible_book_id', 'chapter', 'verse'], ['text', 'updated_at']);
        }

        foreach ($chapterCounts as $bookName => $chapters) {
            $books[$bookName]->update(['chapters' => $chapters]);
        }

        $this->info("Imported {$imported} {$language} {$version} verses.");

        return self::SUCCESS;
    }
}
