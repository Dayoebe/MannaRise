<?php

namespace Database\Seeders;

use App\Models\BibleBook;
use App\Models\BibleVerse;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;

class BibleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $books = collect([
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

        if (BibleVerse::where('version', 'KJV')->count() >= 31102 && ! BibleVerse::where('version', 'KJV')->where('text', 'like', '#%')->exists()) {
            return;
        }

        $url = 'https://raw.githubusercontent.com/farskipper/kjv/master/json/verses-1769.json';
        $response = Http::timeout(60)->get($url);

        if (! $response->successful()) {
            throw new \RuntimeException('Unable to download public-domain KJV Bible JSON.');
        }

        $verses = $response->json();
        $sourceBookAliases = [
            "Solomon's Song" => 'Song of Solomon',
        ];
        $chapterCounts = [];
        $rows = [];
        $now = now();

        foreach ($verses as $reference => $text) {
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
                'version' => 'KJV',
                'chapter' => $chapter,
                'verse' => $verse,
                'text' => trim(str_replace(['[', ']', '#'], '', $text)),
                'created_at' => $now,
                'updated_at' => $now,
            ];

            if (count($rows) >= 1000) {
                BibleVerse::upsert($rows, ['version', 'bible_book_id', 'chapter', 'verse'], ['text', 'updated_at']);
                $rows = [];
            }
        }

        if ($rows !== []) {
            BibleVerse::upsert($rows, ['version', 'bible_book_id', 'chapter', 'verse'], ['text', 'updated_at']);
        }

        foreach ($chapterCounts as $bookName => $chapters) {
            $books[$bookName]->update(['chapters' => $chapters]);
        }
    }
}
