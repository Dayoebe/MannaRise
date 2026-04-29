<?php

namespace App\Support;

use App\Models\Devotional;
use App\Models\SpiritualBook;
use Illuminate\Support\Str;

class Seo
{
    /**
     * @param  array<string, mixed>  $overrides
     * @return array<string, mixed>
     */
    public static function meta(array $overrides = []): array
    {
        $overrides = array_merge(self::forCurrentRoute(), $overrides);

        $title = trim((string) ($overrides['title'] ?? config('seo.title')));
        $description = trim((string) ($overrides['description'] ?? config('seo.description')));
        $siteName = trim((string) config('seo.site_name'));
        $type = $overrides['type'] ?? 'website';
        $canonical = $overrides['canonical'] ?? self::canonicalUrl();
        $image = $overrides['image'] ?? config('seo.image');
        $robots = $overrides['robots'] ?? self::robots();

        return [
            'title' => self::title($title, $siteName),
            'raw_title' => $title,
            'description' => Str::limit(strip_tags($description), 160, ''),
            'canonical' => $canonical,
            'type' => $type,
            'image' => self::absoluteUrl((string) $image),
            'robots' => $robots,
            'site_name' => $siteName,
            'twitter_site' => config('seo.twitter_site'),
            'schema' => $overrides['schema'] ?? self::defaultSchema($canonical, $title, $description),
        ];
    }

    /**
     * @return array<string, mixed>
     */
    public static function forCurrentRoute(): array
    {
        $route = request()->route();
        $routeName = $route?->getName();

        return match ($routeName) {
            'home' => [
                'title' => 'Daily Devotionals, Bible Study, Prayer and Spiritual Growth',
                'description' => 'Start each day with Bible-based devotionals, prayer prompts, journaling, testimonies, memory verses, and spiritual growth tools on MannaRise.',
                'canonical' => route('home'),
            ],
            'daily.index' => [
                'title' => 'Daily Spiritual Rhythm',
                'description' => 'Follow a daily rhythm of scripture, prayer, reflection, affirmation, and spiritual growth with MannaRise.',
                'canonical' => route('daily.index'),
            ],
            'devotionals.index' => [
                'title' => 'Christian Devotionals for Daily Faith and Growth',
                'description' => 'Read practical Christian devotionals with Bible references, reflection questions, prayer points, and declarations for daily spiritual growth.',
                'canonical' => route('devotionals.index'),
            ],
            'devotionals.show' => self::devotionalMeta((string) $route?->parameter('slug')),
            'bible' => [
                'title' => 'Online Bible Reader',
                'description' => 'Read Bible chapters and build a consistent scripture reading rhythm with MannaRise.',
                'canonical' => route('bible'),
            ],
            'library.index' => [
                'title' => 'Christian Spiritual Library',
                'description' => 'Explore public-domain Christian spiritual books and classic faith-building resources in the MannaRise library.',
                'canonical' => route('library.index'),
            ],
            'library.show' => self::bookMeta((string) $route?->parameter('slug')),
            'devotional-plans.index' => [
                'title' => 'Devotional Plans for Spiritual Growth',
                'description' => 'Follow structured devotional plans for prayer, Bible reading, faith, healing, purpose, and consistent Christian growth.',
                'canonical' => route('devotional-plans.index'),
            ],
            'memory-verses.index' => [
                'title' => 'Bible Memory Verses',
                'description' => 'Practice weekly memory verses and build a stronger habit of keeping scripture in your heart.',
                'canonical' => route('memory-verses.index'),
            ],
            'scripture-cards.index' => [
                'title' => 'Scripture Cards for Encouragement',
                'description' => 'Discover scripture cards for prayer, reflection, encouragement, and faith-filled reminders.',
                'canonical' => route('scripture-cards.index'),
            ],
            'prayer-sessions.index' => [
                'title' => 'Guided Prayer Sessions',
                'description' => 'Use guided prayer prompts to pray with clarity, honesty, gratitude, and faith each day.',
                'canonical' => route('prayer-sessions.index'),
            ],
            'audio-devotionals.index' => [
                'title' => 'Audio Devotionals',
                'description' => 'Listen to audio devotionals designed to strengthen faith, prayer, reflection, and spiritual consistency.',
                'canonical' => route('audio-devotionals.index'),
            ],
            'prayer-rooms.index' => [
                'title' => 'Prayer Rooms for Focused Intercession',
                'description' => 'Join focused prayer rooms for healing, family, business, exams, marriage, salvation, and answered-prayer testimonies.',
                'canonical' => route('prayer-rooms.index'),
            ],
            'prayer-requests.wall' => [
                'title' => 'Prayer Wall',
                'description' => 'Pray with others, submit prayer requests, and stand in faith with a growing Christian prayer community.',
                'canonical' => route('prayer-requests.wall'),
            ],
            'prayer-requests.submit' => [
                'title' => 'Submit a Prayer Request',
                'description' => 'Share a prayer request with the MannaRise community and receive spiritual support in prayer.',
                'canonical' => route('prayer-requests.submit'),
            ],
            'testimonies.index' => [
                'title' => 'Christian Testimonies and Answered Prayers',
                'description' => 'Read Christian testimonies of answered prayers, healing, provision, peace, breakthrough, and spiritual growth.',
                'canonical' => route('testimonies.index'),
            ],
            'testimonies.submit' => [
                'title' => 'Share Your Testimony',
                'description' => 'Share what God has done and encourage others with your testimony of faith, prayer, and answered prayers.',
                'canonical' => route('testimonies.submit'),
            ],
            default => [],
        };
    }

    public static function robots(): string
    {
        return app()->environment('production')
            ? (string) config('seo.robots.production')
            : (string) config('seo.robots.non_production');
    }

    public static function canonicalUrl(): string
    {
        return url()->current();
    }

    public static function absoluteUrl(string $path): string
    {
        if (Str::startsWith($path, ['http://', 'https://'])) {
            return $path;
        }

        return url($path);
    }

    /**
     * @return array<int, array<string, mixed>>
     */
    public static function defaultSchema(string $canonical, ?string $title = null, ?string $description = null): array
    {
        $organizationUrl = (string) config('seo.organization.url');
        $organizationLogo = (string) config('seo.organization.logo');

        return [
            [
                '@context' => 'https://schema.org',
                '@type' => 'Organization',
                'name' => config('seo.organization.name'),
                'url' => $organizationUrl,
                'logo' => self::absoluteUrl($organizationLogo),
            ],
            [
                '@context' => 'https://schema.org',
                '@type' => 'WebSite',
                'name' => config('seo.site_name'),
                'url' => $organizationUrl,
                'potentialAction' => [
                    '@type' => 'SearchAction',
                    'target' => url('/devotionals?search={search_term_string}'),
                    'query-input' => 'required name=search_term_string',
                ],
            ],
            [
                '@context' => 'https://schema.org',
                '@type' => 'WebPage',
                'name' => $title ?: config('seo.title'),
                'description' => $description ?: config('seo.description'),
                'url' => $canonical,
                'isPartOf' => [
                    '@type' => 'WebSite',
                    'name' => config('seo.site_name'),
                    'url' => $organizationUrl,
                ],
            ],
        ];
    }

    /**
     * @return array<string, mixed>
     */
    private static function devotionalMeta(string $slug): array
    {
        $devotional = Devotional::query()
            ->with('category')
            ->published()
            ->where('slug', $slug)
            ->first();

        if (! $devotional) {
            return [];
        }

        $description = $devotional->bible_reference
            ? "{$devotional->bible_reference}: ".Str::limit(strip_tags($devotional->content), 135, '')
            : Str::limit(strip_tags($devotional->content), 155, '');

        $canonical = route('devotionals.show', $devotional->slug);

        return [
            'title' => $devotional->title,
            'description' => $description,
            'canonical' => $canonical,
            'type' => 'article',
            'schema' => array_merge(self::defaultSchema($canonical, $devotional->title, $description), [
                [
                    '@context' => 'https://schema.org',
                    '@type' => 'Article',
                    'headline' => $devotional->title,
                    'description' => $description,
                    'datePublished' => $devotional->published_at?->toAtomString(),
                    'dateModified' => $devotional->updated_at?->toAtomString(),
                    'mainEntityOfPage' => $canonical,
                    'articleSection' => $devotional->category?->name,
                    'publisher' => [
                        '@type' => 'Organization',
                        'name' => config('seo.organization.name'),
                        'logo' => [
                            '@type' => 'ImageObject',
                            'url' => self::absoluteUrl((string) config('seo.organization.logo')),
                        ],
                    ],
                ],
            ]),
        ];
    }

    /**
     * @return array<string, mixed>
     */
    private static function bookMeta(string $slug): array
    {
        $book = SpiritualBook::query()
            ->where('slug', $slug)
            ->first();

        if (! $book) {
            return [];
        }

        $description = $book->description ?: "Read {$book->title} by {$book->author} in the MannaRise spiritual library.";
        $canonical = route('library.show', $book->slug);

        return [
            'title' => $book->title,
            'description' => $description,
            'canonical' => $canonical,
            'type' => 'book',
            'schema' => array_merge(self::defaultSchema($canonical, $book->title, $description), [
                [
                    '@context' => 'https://schema.org',
                    '@type' => 'Book',
                    'name' => $book->title,
                    'author' => [
                        '@type' => 'Person',
                        'name' => $book->author,
                    ],
                    'description' => $description,
                    'url' => $canonical,
                    'datePublished' => $book->published_year ? (string) $book->published_year : null,
                    'isAccessibleForFree' => true,
                ],
            ]),
        ];
    }

    private static function title(string $title, string $siteName): string
    {
        if ($title === $siteName || Str::contains($title, $siteName)) {
            return $title;
        }

        return "{$title} | {$siteName}";
    }
}
