<?php

return [
    'default_provider' => env('BIBLE_PROVIDER', 'bible_api_com'),
    'cache_ttl' => (int) env('BIBLE_CACHE_TTL', 86400),
    'default_translation' => env('BIBLE_DEFAULT_TRANSLATION', 'web'),
    'fallback_translation' => env('BIBLE_FALLBACK_TRANSLATION', 'kjv'),

    'providers' => [
        'bible_api_com' => [
            'base_url' => env('BIBLE_API_COM_BASE_URL', 'https://bible-api.com'),
        ],
        'our_manna' => [
            'enabled' => filter_var(env('OUR_MANNA_ENABLED', true), FILTER_VALIDATE_BOOLEAN),
            'base_url' => env('OUR_MANNA_BASE_URL', 'https://beta.ourmanna.com/api/v1'),
        ],
        'api_bible' => [
            'base_url' => env('API_BIBLE_BASE_URL', 'https://api.scripture.api.bible/v1'),
            'key' => env('API_BIBLE_KEY'),
            'bible_id' => env('API_BIBLE_ID'),
        ],
    ],
];
