<?php

return [
    'groups' => [
        [
            'label' => 'Today',
            'items' => [
                [
                    'label' => 'Overview',
                    'route' => 'dashboard',
                    'icon' => 'layout-dashboard',
                    'active' => ['dashboard'],
                ],
                [
                    'label' => 'Daily rhythm',
                    'route' => 'daily.index',
                    'icon' => 'star',
                    'active' => ['daily.*'],
                ],
                [
                    'label' => 'Bible reader',
                    'route' => 'bible',
                    'icon' => 'book-open',
                    'active' => ['bible'],
                ],
                [
                    'label' => 'Bible notes',
                    'route' => 'bible.notes',
                    'icon' => 'bookmark',
                    'active' => ['bible.notes'],
                ],
                [
                    'label' => 'Guided prayer',
                    'route' => 'prayer-sessions.index',
                    'icon' => 'heart',
                    'active' => ['prayer-sessions.*'],
                ],
            ],
        ],
        [
            'label' => 'My Growth',
            'items' => [
                [
                    'label' => 'Growth path',
                    'route' => 'growth-path.index',
                    'icon' => 'route',
                    'active' => ['growth-path.*'],
                ],
                [
                    'label' => 'Journal',
                    'route' => 'journal.index',
                    'icon' => 'journal',
                    'active' => ['journal.*'],
                ],
                [
                    'label' => 'Favorites',
                    'route' => 'favorites.index',
                    'icon' => 'bookmark',
                    'active' => ['favorites.*'],
                ],
                [
                    'label' => 'Reminders',
                    'route' => 'reminders.settings',
                    'icon' => 'bell',
                    'active' => ['reminders.*'],
                ],
                [
                    'label' => 'Offline library',
                    'route' => 'offline.library',
                    'icon' => 'download',
                    'active' => ['offline.*'],
                ],
                [
                    'label' => 'Memory verses',
                    'route' => 'memory-verses.index',
                    'icon' => 'award',
                    'active' => ['memory-verses.*'],
                ],
                [
                    'label' => 'Devotional plans',
                    'route' => 'devotional-plans.index',
                    'icon' => 'route',
                    'active' => ['devotional-plans.*'],
                ],
            ],
        ],
        [
            'label' => 'Community',
            'items' => [
                [
                    'label' => 'Prayer rooms',
                    'route' => 'prayer-rooms.index',
                    'icon' => 'users',
                    'active' => ['prayer-rooms.*'],
                ],
                [
                    'label' => 'Groups',
                    'route' => 'community-groups.index',
                    'icon' => 'users',
                    'active' => ['community-groups.*'],
                ],
                [
                    'label' => 'Prayer wall',
                    'route' => 'prayer-requests.wall',
                    'icon' => 'heart',
                    'active' => ['prayer-requests.wall'],
                ],
                [
                    'label' => 'Testimonies',
                    'route' => 'testimonies.index',
                    'icon' => 'message-circle',
                    'active' => ['testimonies.*'],
                ],
            ],
        ],
        [
            'label' => 'Explore',
            'items' => [
                [
                    'label' => 'Devotionals',
                    'route' => 'devotionals.index',
                    'icon' => 'sparkles',
                    'active' => ['devotionals.*'],
                ],
                [
                    'label' => 'Resource hub',
                    'route' => 'resources.index',
                    'icon' => 'library',
                    'active' => ['resources.*'],
                ],
                [
                    'label' => 'Library',
                    'route' => 'library.index',
                    'icon' => 'library',
                    'active' => ['library.*'],
                ],
                [
                    'label' => 'Scripture cards',
                    'route' => 'scripture-cards.index',
                    'icon' => 'book-open',
                    'active' => ['scripture-cards.*'],
                ],
                [
                    'label' => 'Audio devotionals',
                    'route' => 'audio-devotionals.index',
                    'icon' => 'headphones',
                    'active' => ['audio-devotionals.*'],
                ],
            ],
        ],
        [
            'label' => 'Admin',
            'requires_admin' => true,
            'items' => [
                [
                    'label' => 'Admin home',
                    'route' => 'admin.dashboard',
                    'icon' => 'shield',
                    'active' => ['admin.dashboard'],
                ],
                [
                    'label' => 'Content',
                    'route' => 'admin.devotionals',
                    'icon' => 'sparkles',
                    'active' => ['admin.categories', 'admin.devotionals', 'admin.featured-content'],
                ],
                [
                    'label' => 'Moderation',
                    'route' => 'admin.moderation',
                    'icon' => 'message-circle',
                    'active' => ['admin.moderation', 'admin.prayer-requests', 'admin.testimonies'],
                ],
                [
                    'label' => 'Engagement',
                    'route' => 'admin.engagement',
                    'icon' => 'bar-chart',
                    'active' => ['admin.engagement'],
                ],
                [
                    'label' => 'Users',
                    'route' => 'admin.users',
                    'icon' => 'users',
                    'active' => ['admin.users'],
                    'ability' => 'manage-users',
                ],
                [
                    'label' => 'Roles',
                    'route' => 'admin.roles',
                    'icon' => 'shield',
                    'active' => ['admin.roles'],
                    'ability' => 'manage-roles',
                ],
            ],
        ],
    ],
];
