<?php

return [
    'site_name' => env('SEO_SITE_NAME', 'MannaRise'),

    'title' => env('SEO_TITLE', 'MannaRise | Daily Devotionals, Bible Study, Prayer and Spiritual Growth'),

    'description' => env(
        'SEO_DESCRIPTION',
        'MannaRise is a devotional and spiritual growth platform for daily Bible study, prayer, journaling, testimonies, memory verses, devotional plans, and Christian community.'
    ),

    'image' => env('SEO_IMAGE', '/icons/icon-512.png'),

    'theme_color' => env('SEO_THEME_COLOR', '#047857'),

    'twitter_site' => env('SEO_TWITTER_SITE'),

    'same_as' => array_values(array_filter(array_map('trim', explode(',', (string) env('SEO_SAME_AS', ''))))),

    'contact' => [
        'email' => env('SEO_CONTACT_EMAIL', env('MAIL_REPLY_ADDRESS', env('MAIL_FROM_ADDRESS'))),
    ],

    'organization' => [
        'name' => env('SEO_ORGANIZATION_NAME', 'MannaRise'),
        'url' => env('APP_URL', 'http://localhost'),
        'logo' => env('SEO_ORGANIZATION_LOGO', '/icons/icon-512.png'),
    ],

    'robots' => [
        'production' => 'index,follow,max-image-preview:large,max-snippet:-1,max-video-preview:-1',
        'non_production' => 'noindex,nofollow,noarchive',
        'private' => 'noindex,nofollow,noarchive',
    ],

    'ai_crawlers' => [
        'allow_training' => (bool) env('SEO_ALLOW_AI_TRAINING_CRAWLERS', false),
    ],

    'sitemap' => [
        'static' => [
            ['route' => 'home', 'priority' => '1.0', 'changefreq' => 'daily'],
            ['route' => 'about', 'priority' => '0.7', 'changefreq' => 'monthly'],
            ['route' => 'contact', 'priority' => '0.6', 'changefreq' => 'monthly'],
            ['route' => 'daily.index', 'priority' => '0.9', 'changefreq' => 'daily'],
            ['route' => 'devotionals.index', 'priority' => '0.9', 'changefreq' => 'daily'],
            ['route' => 'bible', 'priority' => '0.8', 'changefreq' => 'weekly'],
            ['route' => 'library.index', 'priority' => '0.8', 'changefreq' => 'weekly'],
            ['route' => 'devotional-plans.index', 'priority' => '0.8', 'changefreq' => 'weekly'],
            ['route' => 'resources.index', 'priority' => '0.8', 'changefreq' => 'daily'],
            ['route' => 'resources.devotion', 'priority' => '0.8', 'changefreq' => 'daily'],
            ['route' => 'resources.books', 'priority' => '0.7', 'changefreq' => 'weekly'],
            ['route' => 'resources.videos', 'priority' => '0.7', 'changefreq' => 'weekly'],
            ['route' => 'resources.audio', 'priority' => '0.7', 'changefreq' => 'weekly'],
            ['route' => 'memory-verses.index', 'priority' => '0.7', 'changefreq' => 'weekly'],
            ['route' => 'scripture-cards.index', 'priority' => '0.7', 'changefreq' => 'weekly'],
            ['route' => 'prayer-sessions.index', 'priority' => '0.7', 'changefreq' => 'weekly'],
            ['route' => 'prayer-invites.show', 'priority' => '0.7', 'changefreq' => 'weekly'],
            ['route' => 'audio-devotionals.index', 'priority' => '0.7', 'changefreq' => 'weekly'],
            ['route' => 'prayer-rooms.index', 'priority' => '0.7', 'changefreq' => 'weekly'],
            ['route' => 'prayer-requests.wall', 'priority' => '0.7', 'changefreq' => 'daily'],
            ['route' => 'testimonies.index', 'priority' => '0.7', 'changefreq' => 'daily'],
        ],
    ],
];
