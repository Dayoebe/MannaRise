<?php

namespace Database\Seeders;

use App\Models\SpiritualBook;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class SpiritualLibrarySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $books = [
            [
                'title' => 'The Imitation of Christ',
                'author' => 'Thomas a Kempis',
                'year' => 1418,
                'featured' => true,
                'description' => 'A classic devotional work on humility, prayer, inner formation, and following Christ with a sincere heart.',
                'chapters' => ['Following Christ', 'A Humble Self-Knowledge', 'The Teaching of Truth', 'Bearing the Cross'],
            ],
            [
                'title' => 'The Practice of the Presence of God',
                'author' => 'Brother Lawrence',
                'year' => 1692,
                'featured' => true,
                'description' => 'A short spiritual classic on communion with God in ordinary work, prayer, and daily attention.',
                'chapters' => ['Presence in Ordinary Work', 'A Habit of Prayer', 'Love Before Method', 'Faithfulness in Small Things'],
            ],
            [
                'title' => 'Confessions',
                'author' => 'Augustine of Hippo',
                'year' => 400,
                'featured' => true,
                'description' => 'A landmark spiritual autobiography reflecting on sin, grace, memory, longing, and the mercy of God.',
                'chapters' => ['Restless Until God', 'Memory and Mercy', 'The Gift of Conversion', 'Grace That Pursues'],
            ],
            [
                'title' => 'Pilgrim\'s Progress',
                'author' => 'John Bunyan',
                'year' => 1678,
                'featured' => false,
                'description' => 'An allegorical journey of faith, perseverance, temptation, courage, and arrival in the city of God.',
                'chapters' => ['The Burden and the Gate', 'The Narrow Way', 'The Valley of Difficulty', 'Hope on the Journey'],
            ],
            [
                'title' => 'A Serious Call to a Devout and Holy Life',
                'author' => 'William Law',
                'year' => 1729,
                'featured' => false,
                'description' => 'A practical call to disciplined devotion, prayerful habits, generosity, and wholehearted Christian living.',
                'chapters' => ['Devotion in Daily Life', 'The Use of Time', 'Prayer and Simplicity', 'Generosity of Heart'],
            ],
            [
                'title' => 'The Interior Castle',
                'author' => 'Teresa of Avila',
                'year' => 1577,
                'featured' => false,
                'description' => 'A contemplative classic describing prayer, interior transformation, self-knowledge, and union with God.',
                'chapters' => ['Entering the Castle', 'Rooms of Prayer', 'Humility and Self-Knowledge', 'Love Made Practical'],
            ],
            [
                'title' => 'Dark Night of the Soul',
                'author' => 'John of the Cross',
                'year' => 1578,
                'featured' => false,
                'description' => 'A spiritual text on purification, hidden growth, longing, and trust when God seems silent.',
                'chapters' => ['When Consolation Fades', 'Purified Desire', 'Faith in Darkness', 'Love Beyond Feeling'],
            ],
            [
                'title' => 'On the Incarnation',
                'author' => 'Athanasius of Alexandria',
                'year' => 318,
                'featured' => false,
                'description' => 'A foundational Christian theological work on Christ, redemption, and the Word made flesh.',
                'chapters' => ['The Word Made Flesh', 'Restoring the Image', 'The Defeat of Death', 'Knowing God in Christ'],
            ],
            [
                'title' => 'The Cloud of Unknowing',
                'author' => 'Anonymous',
                'year' => 1375,
                'featured' => false,
                'description' => 'A contemplative guide to prayer, surrender, and loving attention toward God beyond mere analysis.',
                'chapters' => ['Loving Attention', 'Beyond Mere Knowing', 'A Short Word of Prayer', 'Humility in Contemplation'],
            ],
            [
                'title' => 'Revelations of Divine Love',
                'author' => 'Julian of Norwich',
                'year' => 1395,
                'featured' => false,
                'description' => 'A devotional classic reflecting on God\'s love, mercy, hope, and sustaining care.',
                'chapters' => ['The Love of God', 'Hope in Mercy', 'Held in Grace', 'All Shall Be Well'],
            ],
            [
                'title' => 'Morning and Evening Selections',
                'author' => 'Charles H. Spurgeon',
                'year' => 1865,
                'featured' => false,
                'description' => 'Devotional readings inspired by Spurgeon\'s public-domain devotional tradition of morning and evening reflection.',
                'chapters' => ['Morning Trust', 'Evening Rest', 'Christ Our Portion', 'Grace for Weariness'],
            ],
            [
                'title' => 'The Rule of Benedict',
                'author' => 'Benedict of Nursia',
                'year' => 516,
                'featured' => false,
                'description' => 'A foundational text on prayer, community, humility, work, and ordered spiritual life.',
                'chapters' => ['Listening With the Heart', 'Prayer and Work', 'Humility in Community', 'Stability and Peace'],
            ],
        ];

        foreach ($books as $bookData) {
            $book = SpiritualBook::updateOrCreate(
                ['slug' => Str::slug($bookData['title'])],
                [
                    'title' => $bookData['title'],
                    'slug' => Str::slug($bookData['title']),
                    'author' => $bookData['author'],
                    'tradition' => 'Christian Classic',
                    'source' => 'Public domain classic; chapter readings curated for MannaRise.',
                    'published_year' => $bookData['year'],
                    'description' => $bookData['description'],
                    'is_public_domain' => true,
                    'is_featured' => $bookData['featured'],
                ],
            );

            foreach ($bookData['chapters'] as $index => $chapterTitle) {
                $number = $index + 1;
                $content = "{$chapterTitle}\n\nThis reading draws from the spiritual tradition of {$bookData['title']} and invites slow attention rather than hurried consumption. Read with prayer, asking what truth should become obedience today.\n\nThe central movement is toward love of God, humility of heart, and faithful action in ordinary life. A spiritual text is not merely information; it becomes fruitful when it trains desire, corrects pride, and strengthens perseverance.\n\nPause after this chapter and name one practice you can carry into the next hour. It may be silence, confession, gratitude, forgiveness, intercession, or a simple act of service.";

                $book->chapters()->updateOrCreate(
                    ['chapter_number' => $number],
                    [
                        'title' => $chapterTitle,
                        'content' => $content,
                    ],
                );
            }
        }
    }
}
