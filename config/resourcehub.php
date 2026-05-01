<?php

return [
    'cache_ttl' => (int) env('RESOURCEHUB_CACHE_TTL', 86400),

    'providers' => [
        'bible_api' => [
            'enabled' => true,
            'base_url' => 'https://bible-api.com',
        ],
        'api_bible' => [
            'enabled' => (bool) env('API_BIBLE_KEY'),
            'base_url' => 'https://api.scripture.api.bible/v1',
            'key' => env('API_BIBLE_KEY'),
            'bible_id' => env('API_BIBLE_ID'),
        ],
        'gutendex' => [
            'enabled' => true,
            'base_url' => 'https://gutendex.com',
        ],
        'open_library' => [
            'enabled' => true,
            'base_url' => 'https://openlibrary.org',
        ],
        'librivox' => [
            'enabled' => true,
            'base_url' => 'https://librivox.org/api/feed',
        ],
        'internet_archive' => [
            'enabled' => true,
            'base_url' => 'https://archive.org',
        ],
        'youtube' => [
            'enabled' => (bool) env('YOUTUBE_API_KEY'),
            'base_url' => 'https://www.googleapis.com/youtube/v3',
            'key' => env('YOUTUBE_API_KEY'),
        ],
    ],

    'default_categories' => [
        ['name' => 'Daily Devotion', 'type' => 'devotion', 'icon' => 'sparkles', 'description' => 'Daily spiritual guidance, prayer, and reflection.'],
        ['name' => 'Bible Study', 'type' => 'bible', 'icon' => 'book-open', 'description' => 'Bible verses, scripture references, and study helps.'],
        ['name' => 'Books', 'type' => 'book', 'icon' => 'library', 'description' => 'Free public-domain Christian and educational books.'],
        ['name' => 'Videos', 'type' => 'video', 'icon' => 'video', 'description' => 'Teaching, sermons, and educational video resources.'],
        ['name' => 'Audio', 'type' => 'audio', 'icon' => 'headphones', 'description' => 'Sermons, devotionals, teachings, and audiobooks.'],
        ['name' => 'Education', 'type' => 'education', 'icon' => 'journal', 'description' => 'Faith-friendly learning and growth resources.'],
    ],

    'book_keywords' => ['christian faith', 'prayer', 'bible study', 'spiritual growth'],
    'audio_keywords' => ['bible', 'christian', 'devotional', 'prayer'],
    'youtube_keywords' => ['Christian devotional', 'Bible study', 'prayer teaching'],
];
