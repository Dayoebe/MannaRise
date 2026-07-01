<?php

namespace App\Support;

use App\Models\DailyDevotion;
use App\Models\Devotional;
use App\Models\PrayerRoom;
use App\Models\ResourceItem;
use App\Models\SpiritualBook;
use Carbon\CarbonImmutable;
use DateTimeInterface;
use Illuminate\Support\Str;
use Throwable;

class Seo
{
    /**
     * @param  array<string, mixed>  $overrides
     * @return array<string, mixed>
     */
    public static function meta(array $overrides = []): array
    {
        $overrides = array_merge(self::forCurrentRoute(), $overrides);

        $siteName = trim((string) config('seo.site_name'));
        $rawTitle = trim((string) ($overrides['title'] ?? config('seo.title')));
        $description = self::plainText((string) ($overrides['description'] ?? config('seo.description')));
        $canonical = self::absoluteUrl((string) ($overrides['canonical'] ?? self::canonicalUrl()));
        $image = self::absoluteUrl((string) ($overrides['image'] ?? config('seo.image')));
        $type = $overrides['type'] ?? 'website';
        $breadcrumbs = $overrides['breadcrumbs'] ?? self::homeBreadcrumb();
        $language = (string) ($overrides['language'] ?? str_replace('_', '-', app()->getLocale()));

        return [
            'title' => self::title($rawTitle, $siteName),
            'raw_title' => $rawTitle,
            'description' => Str::limit($description, 160, ''),
            'canonical' => $canonical,
            'type' => $type,
            'image' => $image,
            'image_alt' => $overrides['image_alt'] ?? $rawTitle,
            'robots' => $overrides['robots'] ?? self::robots(),
            'site_name' => $siteName,
            'twitter_site' => config('seo.twitter_site'),
            'breadcrumbs' => $breadcrumbs,
            'schema' => $overrides['schema'] ?? self::defaultSchema($canonical, $rawTitle, $description, $breadcrumbs, language: $language),
            'language' => $language,
            'locale_code' => $overrides['locale_code'] ?? $language,
            'og_locale' => $overrides['og_locale'] ?? str_replace('-', '_', $language),
            'alternates' => $overrides['alternates'] ?? [],
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
                'title' => 'MannaRise | Daily Devotionals, Bible Study, Prayer and Spiritual Growth',
                'description' => 'MannaRise is a Christian spiritual growth platform for daily Bible-based devotionals, prayer prompts, journaling, testimonies, memory verses, devotional plans, and community prayer.',
                'canonical' => route('home'),
                'language' => LanguagePages::language('en')['html_locale'],
                'locale_code' => 'en',
                'og_locale' => LanguagePages::language('en')['og_locale'],
                'alternates' => LanguagePages::homeAlternates(),
                'breadcrumbs' => self::homeBreadcrumb(),
                'schema' => self::homeSchema(),
            ],
            'localized.home' => LanguagePages::homeMeta((string) $route?->parameter('locale')),
            'about' => self::pageMeta(
                'About MannaRise',
                'Learn what MannaRise offers: daily devotionals, Bible study, prayer, journaling, testimonies, memory verses, devotional plans, and Christian community tools.',
                route('about'),
                [['About', route('about')]]
            ),
            'contact' => self::pageMeta(
                'Contact MannaRise',
                'Find the configured public contact details for MannaRise and links for prayer requests, testimonies, and community support.',
                route('contact'),
                [['Contact', route('contact')]],
                schemaType: 'ContactPage'
            ),
            'daily.index' => self::collectionMeta(
                'Daily Spiritual Rhythm',
                'Follow a daily rhythm of scripture, prayer, reflection, affirmation, and spiritual growth with MannaRise.',
                route('daily.index'),
                [['Daily', route('daily.index')]]
            ),
            'daily.show', 'daily.localized.show' => self::dailyPermalinkMeta((string) $route?->parameter('date'), (string) $route?->parameter('locale')),
            'devotionals.index' => self::collectionMeta(
                'Christian Devotionals for Daily Faith and Growth',
                'Read practical Christian devotionals with Bible references, reflection questions, prayer points, and declarations for daily spiritual growth.',
                route('devotionals.index'),
                [['Devotionals', route('devotionals.index')]]
            ),
            'devotionals.show' => self::devotionalMeta((string) $route?->parameter('slug')),
            'bible' => self::pageMeta(
                'Online Bible Reader',
                'Read Bible chapters, use study mode, save notes, and build a consistent scripture reading rhythm with MannaRise.',
                route('bible'),
                [['Bible', route('bible')]]
            ),
            'library.index' => self::collectionMeta(
                'Christian Spiritual Library',
                'Explore public-domain Christian spiritual books and classic faith-building resources in the MannaRise library.',
                route('library.index'),
                [['Library', route('library.index')]]
            ),
            'library.show' => self::bookMeta((string) $route?->parameter('slug')),
            'devotional-plans.index' => self::collectionMeta(
                'Devotional Plans for Spiritual Growth',
                'Follow structured devotional plans for prayer, Bible reading, faith, healing, purpose, and consistent Christian growth.',
                route('devotional-plans.index'),
                [['Plans', route('devotional-plans.index')]]
            ),
            'devotional-plans.show' => self::planMeta((string) $route?->parameter('plan')),
            'resources.index' => self::collectionMeta(
                'Christian Resource Hub',
                'Browse spiritual books, devotion guides, Bible resources, videos, audio teaching, sermons, and educational resources for daily growth.',
                route('resources.index'),
                [['Resources', route('resources.index')]]
            ),
            'resources.devotion', 'resources.devotion.show' => self::dailyDevotionMeta((string) $route?->parameter('slug')),
            'resources.books' => self::collectionMeta(
                'Christian Books and Public-Domain Resources',
                'Browse Christian books and public-domain spiritual resources available in the MannaRise resource hub.',
                route('resources.books'),
                [['Resources', route('resources.index')], ['Books', route('resources.books')]]
            ),
            'resources.videos' => self::collectionMeta(
                'Christian Videos',
                'Watch Christian videos and spiritual teaching resources collected for daily growth.',
                route('resources.videos'),
                [['Resources', route('resources.index')], ['Videos', route('resources.videos')]]
            ),
            'resources.audio' => self::collectionMeta(
                'Christian Audio Resources',
                'Listen to Christian audio resources, sermons, and devotional teaching collected for daily growth.',
                route('resources.audio'),
                [['Resources', route('resources.index')], ['Audio', route('resources.audio')]]
            ),
            'resources.show' => self::resourceMeta((string) $route?->parameter('slug')),
            'memory-verses.index' => self::collectionMeta(
                'Bible Memory Verses',
                'Practice weekly memory verses and build a stronger habit of keeping scripture in your heart.',
                route('memory-verses.index'),
                [['Memory Verses', route('memory-verses.index')]]
            ),
            'scripture-cards.index' => self::collectionMeta(
                'Scripture Cards for Encouragement',
                'Discover scripture cards for prayer, reflection, encouragement, and faith-filled reminders.',
                route('scripture-cards.index'),
                [['Scripture Cards', route('scripture-cards.index')]]
            ),
            'prayer-sessions.index' => self::collectionMeta(
                'Guided Prayer Sessions',
                'Use guided prayer prompts to pray with clarity, honesty, gratitude, and faith each day.',
                route('prayer-sessions.index'),
                [['Guided Prayer', route('prayer-sessions.index')]]
            ),
            'audio-devotionals.index' => self::collectionMeta(
                'Audio Devotionals',
                'Listen to audio devotionals designed to strengthen faith, prayer, reflection, and spiritual consistency.',
                route('audio-devotionals.index'),
                [['Audio Devotionals', route('audio-devotionals.index')]]
            ),
            'prayer-rooms.index' => self::collectionMeta(
                'Prayer Rooms for Focused Intercession',
                'Join focused prayer rooms for healing, family, business, exams, marriage, salvation, and answered-prayer testimonies.',
                route('prayer-rooms.index'),
                [['Prayer Rooms', route('prayer-rooms.index')]]
            ),
            'prayer-rooms.show' => self::prayerRoomMeta((string) $route?->parameter('room')),
            'prayer-invites.show' => self::prayerInviteMeta((string) $route?->parameter('devotionalSlug')),
            'prayer-requests.wall' => self::collectionMeta(
                'Prayer Wall',
                'Pray with others, submit prayer requests, and stand in faith with a growing Christian prayer community.',
                route('prayer-requests.wall'),
                [['Prayer Wall', route('prayer-requests.wall')]]
            ),
            'prayer-requests.submit' => self::pageMeta(
                'Submit a Prayer Request',
                'Share a prayer request with the MannaRise community and receive spiritual support in prayer.',
                route('prayer-requests.submit'),
                [['Prayer Request', route('prayer-requests.submit')]]
            ),
            'testimonies.index' => self::collectionMeta(
                'Christian Testimonies and Answered Prayers',
                'Read Christian testimonies of answered prayers, healing, provision, peace, breakthrough, and spiritual growth.',
                route('testimonies.index'),
                [['Testimonies', route('testimonies.index')]]
            ),
            'testimonies.submit' => self::pageMeta(
                'Share Your Testimony',
                'Share what God has done and encourage others with your testimony of faith, prayer, and answered prayers.',
                route('testimonies.submit'),
                [['Share Testimony', route('testimonies.submit')]]
            ),
            'login' => self::privateMeta('Log in', route('login')),
            'register' => self::privateMeta('Create an Account', route('register')),
            default => [],
        };
    }

    public static function robots(): string
    {
        if (self::isPrivateRoute() || self::hasIndexableDuplicateQuery()) {
            return (string) config('seo.robots.private');
        }

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
        if ($path === '') {
            $path = (string) config('seo.image');
        }

        if (Str::startsWith($path, ['http://', 'https://'])) {
            return $path;
        }

        return url($path);
    }

    public static function summarize(?string $text, int $words = 52): string
    {
        return Str::words(self::plainText((string) $text), $words);
    }

    /**
     * @param  array<int, array{0:string,1:string|null}>  $tail
     * @return array<int, array{label:string,url:string|null}>
     */
    public static function breadcrumbs(array|string $tail = [], ?string $url = null): array
    {
        $items = self::homeBreadcrumb();

        if (is_string($tail)) {
            $tail = [[$tail, $url]];
        }

        foreach ($tail as $item) {
            if (($item[1] ?? null) === route('home')) {
                continue;
            }

            $items[] = ['label' => $item[0], 'url' => $item[1] ?? null];
        }

        return $items;
    }

    /**
     * @return array<int, array{label:string,url:string}>
     */
    private static function homeBreadcrumb(): array
    {
        return [['label' => 'Home', 'url' => route('home')]];
    }

    /**
     * @param  array<int, array{label:string,url:string|null}>  $breadcrumbs
     * @return array<int, array<string, mixed>>
     */
    public static function defaultSchema(string $canonical, ?string $title = null, ?string $description = null, array $breadcrumbs = [], string $schemaType = 'WebPage', ?string $language = null): array
    {
        return self::schemaGraph(self::defaultGraphNodes($canonical, $title, $description, $breadcrumbs, $schemaType, $language));
    }

    private static function pageMeta(string $title, string $description, string $canonical, array $breadcrumbTail, string $schemaType = 'WebPage'): array
    {
        $breadcrumbs = self::breadcrumbs($breadcrumbTail);

        return [
            'title' => $title,
            'description' => $description,
            'canonical' => $canonical,
            'breadcrumbs' => $breadcrumbs,
            'schema' => self::defaultSchema($canonical, $title, $description, $breadcrumbs, $schemaType),
        ];
    }

    private static function collectionMeta(string $title, string $description, string $canonical, array $breadcrumbTail): array
    {
        return self::pageMeta($title, $description, $canonical, $breadcrumbTail, 'CollectionPage');
    }

    private static function dailyPermalinkMeta(string $date, string $locale = ''): array
    {
        try {
            $carbonDate = CarbonImmutable::createFromFormat('!Y-m-d', $date);
        } catch (Throwable) {
            return [];
        }

        if (! $carbonDate || $carbonDate->format('Y-m-d') !== $date) {
            return [];
        }

        $canonical = $locale !== ''
            ? route('daily.localized.show', ['locale' => $locale, 'date' => $date])
            : route('daily.show', ['date' => $date]);

        return LanguagePages::dailyMeta($locale !== '' ? $locale : 'en', $carbonDate, $canonical);
    }

    private static function privateMeta(string $title, string $canonical): array
    {
        return [
            'title' => $title,
            'description' => config('seo.description'),
            'canonical' => $canonical,
            'robots' => (string) config('seo.robots.private'),
            'breadcrumbs' => [],
        ];
    }

    /**
     * @return array<string, mixed>
     */
    private static function devotionalMeta(string $slug): array
    {
        $devotional = Devotional::query()
            ->with(['category', 'author'])
            ->published()
            ->where('slug', $slug)
            ->first();

        if (! $devotional) {
            return [];
        }

        $summary = self::summarize($devotional->content, 34);
        $description = $devotional->bible_reference
            ? "{$devotional->bible_reference}: {$summary}"
            : $summary;
        $canonical = route('devotionals.show', $devotional->slug);
        $breadcrumbs = self::breadcrumbs([
            ['Devotionals', route('devotionals.index')],
            [$devotional->title, $canonical],
        ]);
        $authorName = $devotional->author?->name ?: (string) config('seo.site_name');

        $nodes = self::defaultGraphNodes($canonical, $devotional->title, $description, $breadcrumbs, 'WebPage');
        $nodes[] = [
            '@type' => 'BlogPosting',
            '@id' => $canonical.'#article',
            'headline' => $devotional->title,
            'description' => $description,
            'image' => self::absoluteUrl((string) config('seo.image')),
            'datePublished' => $devotional->published_at,
            'dateModified' => $devotional->updated_at,
            'author' => self::authorNode($authorName),
            'publisher' => ['@id' => self::siteUrl('/#organization')],
            'mainEntityOfPage' => ['@id' => $canonical.'#webpage'],
            'articleSection' => $devotional->category?->name,
            'keywords' => array_values(array_filter([$devotional->category?->name, $devotional->bible_reference])),
            'wordCount' => str_word_count(self::plainText($devotional->content)),
            'timeRequired' => $devotional->reading_time ? 'PT'.$devotional->reading_time.'M' : null,
            'isAccessibleForFree' => true,
        ];

        return [
            'title' => $devotional->title,
            'description' => $description,
            'canonical' => $canonical,
            'type' => 'article',
            'breadcrumbs' => $breadcrumbs,
            'schema' => self::schemaGraph($nodes),
        ];
    }

    /**
     * @return array<string, mixed>
     */
    private static function prayerInviteMeta(string $slug = ''): array
    {
        $devotional = $slug !== ''
            ? Devotional::query()->published()->where('slug', $slug)->first()
            : null;

        if (! $devotional) {
            return self::pageMeta(
                'Pray With Me',
                'Invite someone to pray with you through a public MannaRise prayer page with guided prayer, Scripture, and prayer request actions.',
                route('prayer-invites.show'),
                [['Pray With Me', route('prayer-invites.show')]]
            );
        }

        $title = 'Pray With Me: '.$devotional->title;
        $description = 'Pray through '.$devotional->title.' with a shared MannaRise page for guided prayer, Scripture, and encouragement.';
        $canonical = route('prayer-invites.show', ['devotionalSlug' => $devotional->slug]);

        return self::pageMeta(
            $title,
            $description,
            $canonical,
            [
                ['Devotionals', route('devotionals.index')],
                [$devotional->title, route('devotionals.show', $devotional->slug)],
                ['Pray With Me', $canonical],
            ]
        );
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

        $description = $book->description ?: trim("Read {$book->title} by {$book->author} in the MannaRise spiritual library.");
        $canonical = route('library.show', $book->slug);
        $breadcrumbs = self::breadcrumbs([
            ['Library', route('library.index')],
            [$book->title, $canonical],
        ]);

        $nodes = self::defaultGraphNodes($canonical, $book->title, $description, $breadcrumbs, 'WebPage');
        $nodes[] = [
            '@type' => 'Book',
            '@id' => $canonical.'#book',
            'name' => $book->title,
            'author' => $book->author ? self::authorNode($book->author) : null,
            'description' => $description,
            'url' => $canonical,
            'datePublished' => $book->published_year ? (string) $book->published_year : null,
            'isAccessibleForFree' => true,
        ];

        return [
            'title' => $book->title,
            'description' => $description,
            'canonical' => $canonical,
            'type' => 'book',
            'breadcrumbs' => $breadcrumbs,
            'schema' => self::schemaGraph($nodes),
        ];
    }

    /**
     * @return array<string, mixed>
     */
    private static function resourceMeta(string $slug): array
    {
        $resource = ResourceItem::query()
            ->with('category')
            ->published()
            ->where('slug', $slug)
            ->first();

        if (! $resource) {
            return [];
        }

        $description = $resource->excerpt
            ?: self::summarize($resource->description ?: $resource->content, 32);
        $canonical = route('resources.show', $resource->slug);
        $breadcrumbs = self::breadcrumbs([
            ['Resources', route('resources.index')],
            [$resource->title, $canonical],
        ]);
        $schemaImage = $resource->thumbnail_url ? self::absoluteUrl($resource->thumbnail_url) : null;
        $image = $schemaImage ?: self::absoluteUrl((string) config('seo.image'));
        $nodes = self::defaultGraphNodes($canonical, $resource->title, $description, $breadcrumbs, 'WebPage');
        $nodes[] = self::resourceSchemaNode($resource, $canonical, $description, $schemaImage);

        return [
            'title' => $resource->title,
            'description' => $description,
            'canonical' => $canonical,
            'type' => $resource->type === 'video' ? 'video.other' : 'article',
            'image' => $image,
            'image_alt' => $resource->title,
            'breadcrumbs' => $breadcrumbs,
            'schema' => self::schemaGraph($nodes),
        ];
    }

    /**
     * @return array<string, mixed>
     */
    private static function dailyDevotionMeta(?string $slug = null): array
    {
        $devotion = $slug
            ? DailyDevotion::query()->published()->where('slug', $slug)->first()
            : DailyDevotion::query()->published()->latest('devotion_date')->first();

        if (! $devotion) {
            return self::pageMeta(
                'Daily Resource Devotion',
                'Read today\'s scripture, devotion guide, prayer, reflection questions, and action point from the MannaRise resource hub.',
                route('resources.devotion'),
                [['Resources', route('resources.index')], ['Daily Devotion', route('resources.devotion')]]
            );
        }

        $description = $devotion->bible_reference
            ? "{$devotion->bible_reference}: ".self::summarize($devotion->devotion_text, 32)
            : self::summarize($devotion->devotion_text, 34);
        $canonical = route('resources.devotion.show', $devotion->slug);
        $breadcrumbs = self::breadcrumbs([
            ['Resources', route('resources.index')],
            ['Daily Devotion', route('resources.devotion')],
            [$devotion->title, $canonical],
        ]);

        $nodes = self::defaultGraphNodes($canonical, $devotion->title, $description, $breadcrumbs, 'WebPage');
        $nodes[] = [
            '@type' => 'BlogPosting',
            '@id' => $canonical.'#article',
            'headline' => $devotion->title,
            'description' => $description,
            'datePublished' => $devotion->devotion_date,
            'dateModified' => $devotion->updated_at,
            'author' => self::authorNode($devotion->author ?: (string) config('seo.site_name')),
            'publisher' => ['@id' => self::siteUrl('/#organization')],
            'mainEntityOfPage' => ['@id' => $canonical.'#webpage'],
            'articleSection' => 'Daily devotion',
            'keywords' => array_values(array_filter(['daily devotion', $devotion->bible_reference])),
            'isAccessibleForFree' => true,
        ];

        return [
            'title' => $devotion->title,
            'description' => $description,
            'canonical' => $canonical,
            'type' => 'article',
            'breadcrumbs' => $breadcrumbs,
            'schema' => self::schemaGraph($nodes),
        ];
    }

    private static function planMeta(string $slug): array
    {
        $plan = DevotionalPlans::find($slug);

        if (! $plan) {
            return [];
        }

        $canonical = route('devotional-plans.show', $plan['slug']);
        $breadcrumbs = self::breadcrumbs([
            ['Plans', route('devotional-plans.index')],
            [$plan['title'], $canonical],
        ]);
        $nodes = self::defaultGraphNodes($canonical, $plan['title'], $plan['description'], $breadcrumbs, 'WebPage');
        $nodes[] = [
            '@type' => 'Course',
            '@id' => $canonical.'#course',
            'name' => $plan['title'],
            'description' => $plan['description'],
            'url' => $canonical,
            'provider' => ['@id' => self::siteUrl('/#organization')],
            'timeRequired' => 'P'.$plan['duration'].'D',
            'teaches' => $plan['focuses'],
            'isAccessibleForFree' => true,
        ];

        return [
            'title' => $plan['title'],
            'description' => $plan['description'],
            'canonical' => $canonical,
            'breadcrumbs' => $breadcrumbs,
            'schema' => self::schemaGraph($nodes),
        ];
    }

    private static function prayerRoomMeta(string $slug): array
    {
        $room = PrayerRoom::query()
            ->where('slug', $slug)
            ->where('is_active', true)
            ->first();

        if (! $room) {
            $room = collect(PrayerRoom::defaults())->firstWhere('slug', $slug);
            if (! $room) {
                return [];
            }

            $title = $room['name'].' prayer room';
            $description = $room['description'];
            $scripture = $room['scripture_reference'];
        } else {
            $title = $room->name.' prayer room';
            $description = $room->description;
            $scripture = $room->scripture_reference;
        }

        $canonical = route('prayer-rooms.show', $slug);
        $breadcrumbs = self::breadcrumbs([
            ['Prayer Rooms', route('prayer-rooms.index')],
            [$title, $canonical],
        ]);
        $nodes = self::defaultGraphNodes($canonical, $title, $description, $breadcrumbs, 'CollectionPage');
        $nodes[] = [
            '@type' => 'CollectionPage',
            '@id' => $canonical.'#room',
            'name' => $title,
            'description' => $description,
            'about' => $scripture,
            'isPartOf' => ['@id' => self::siteUrl('/#website')],
        ];

        return [
            'title' => $title,
            'description' => $description,
            'canonical' => $canonical,
            'breadcrumbs' => $breadcrumbs,
            'schema' => self::schemaGraph($nodes),
        ];
    }

    /**
     * @return array<int, array<string, mixed>>
     */
    private static function homeSchema(): array
    {
        $canonical = route('home');
        $description = (string) config('seo.description');
        $breadcrumbs = self::homeBreadcrumb();
        $nodes = self::defaultGraphNodes($canonical, (string) config('seo.site_name'), $description, $breadcrumbs, 'WebPage');

        return self::schemaGraph($nodes);
    }

    /**
     * @param  array<int, array{label:string,url:string|null}>  $breadcrumbs
     * @return array<int, array<string, mixed>>
     */
    private static function defaultGraphNodes(string $canonical, ?string $title, ?string $description, array $breadcrumbs, string $schemaType, ?string $language = null): array
    {
        $nodes = [
            self::organizationNode(),
            self::websiteNode(),
            [
                '@type' => $schemaType,
                '@id' => $canonical.'#webpage',
                'url' => $canonical,
                'name' => $title ?: config('seo.title'),
                'description' => $description ?: config('seo.description'),
                'isPartOf' => ['@id' => self::siteUrl('/#website')],
                'about' => ['@id' => self::siteUrl('/#organization')],
                'inLanguage' => $language ?: str_replace('_', '-', app()->getLocale()),
            ],
        ];

        if (count($breadcrumbs) > 1) {
            $nodes[] = self::breadcrumbNode($canonical, $breadcrumbs);
            $nodes[2]['breadcrumb'] = ['@id' => $canonical.'#breadcrumb'];
        }

        return $nodes;
    }

    /**
     * @return array<string, mixed>
     */
    private static function organizationNode(): array
    {
        $sameAs = config('seo.same_as', []);
        $email = config('seo.contact.email');

        return [
            '@type' => 'Organization',
            '@id' => self::siteUrl('/#organization'),
            'name' => config('seo.organization.name'),
            'url' => self::siteUrl('/'),
            'logo' => [
                '@type' => 'ImageObject',
                'url' => self::absoluteUrl((string) config('seo.organization.logo')),
            ],
            'sameAs' => $sameAs,
            'contactPoint' => $email ? [[
                '@type' => 'ContactPoint',
                'contactType' => 'customer support',
                'email' => $email,
                'availableLanguage' => ['English'],
            ]] : null,
        ];
    }

    /**
     * @return array<string, mixed>
     */
    private static function websiteNode(): array
    {
        return [
            '@type' => 'WebSite',
            '@id' => self::siteUrl('/#website'),
            'name' => config('seo.site_name'),
            'url' => self::siteUrl('/'),
            'publisher' => ['@id' => self::siteUrl('/#organization')],
            'inLanguage' => str_replace('_', '-', app()->getLocale()),
        ];
    }

    /**
     * @param  array<int, array{label:string,url:string|null}>  $breadcrumbs
     * @return array<string, mixed>
     */
    private static function breadcrumbNode(string $canonical, array $breadcrumbs): array
    {
        return [
            '@type' => 'BreadcrumbList',
            '@id' => $canonical.'#breadcrumb',
            'itemListElement' => collect($breadcrumbs)->values()->map(fn (array $item, int $index): array => [
                '@type' => 'ListItem',
                'position' => $index + 1,
                'name' => $item['label'],
                'item' => $item['url'],
            ])->all(),
        ];
    }

    /**
     * @return array<string, mixed>
     */
    private static function resourceSchemaNode(ResourceItem $resource, string $canonical, string $description, ?string $image): array
    {
        $base = [
            '@id' => $canonical.'#resource',
            'name' => $resource->title,
            'description' => $description,
            'url' => $canonical,
            'datePublished' => $resource->published_at,
            'dateModified' => $resource->updated_at,
            'author' => $resource->author ? self::authorNode($resource->author) : null,
            'publisher' => ['@id' => self::siteUrl('/#organization')],
            'provider' => $resource->source_name ? [
                '@type' => 'Organization',
                'name' => $resource->source_name,
                'url' => $resource->source_url,
            ] : ['@id' => self::siteUrl('/#organization')],
            'isAccessibleForFree' => true,
            'license' => $resource->license,
            'keywords' => $resource->tags,
        ];

        return match ($resource->type) {
            'video' => [
                ...$base,
                '@type' => 'VideoObject',
                'thumbnailUrl' => $image ? [$image] : null,
                'uploadDate' => $resource->published_at,
                'embedUrl' => $resource->embed_url,
                'contentUrl' => $resource->media_url ?: $resource->source_url,
            ],
            'audio', 'sermon' => [
                ...$base,
                '@type' => 'AudioObject',
                'contentUrl' => $resource->media_url ?: $resource->source_url,
                'encodingFormat' => $resource->media_url ? pathinfo(parse_url($resource->media_url, PHP_URL_PATH) ?: '', PATHINFO_EXTENSION) : null,
            ],
            'book' => [
                ...$base,
                '@type' => 'Book',
                'image' => $image,
            ],
            'article', 'devotion', 'education' => [
                ...$base,
                '@type' => 'Article',
                'headline' => $resource->title,
                'image' => $image,
                'articleSection' => $resource->category?->name,
                'mainEntityOfPage' => ['@id' => $canonical.'#webpage'],
            ],
            default => [
                ...$base,
                '@type' => 'CreativeWork',
                'image' => $image,
            ],
        };
    }

    /**
     * @param  array<int, array<string, mixed>>  $nodes
     * @return array<int, array<string, mixed>>
     */
    private static function schemaGraph(array $nodes): array
    {
        return [
            self::clean([
                '@context' => 'https://schema.org',
                '@graph' => array_values($nodes),
            ]),
        ];
    }

    /**
     * @return array<string, mixed>
     */
    private static function authorNode(string $name): array
    {
        if ($name === config('seo.site_name') || $name === config('seo.organization.name')) {
            return ['@id' => self::siteUrl('/#organization')];
        }

        return [
            '@type' => 'Person',
            'name' => $name,
        ];
    }

    private static function title(string $title, string $siteName): string
    {
        if ($title === $siteName || Str::contains($title, $siteName)) {
            return $title;
        }

        return "{$title} | {$siteName}";
    }

    private static function siteUrl(string $path): string
    {
        return rtrim((string) config('seo.organization.url'), '/').'/'.ltrim($path, '/');
    }

    private static function plainText(string $value): string
    {
        return Str::of(strip_tags($value))->replaceMatches('/\s+/', ' ')->trim()->toString();
    }

    private static function isPrivateRoute(): bool
    {
        return request()->routeIs(
            'admin.*',
            'dashboard',
            'onboarding',
            'growth-path.*',
            'journal.*',
            'bible.notes',
            'favorites.*',
            'reminders.*',
            'offline.*',
            'community-groups.*',
            'mail.*',
            'login',
            'register',
        );
    }

    private static function hasIndexableDuplicateQuery(): bool
    {
        return collect(['q', 'search', 'type', 'category', 'status', 'page'])
            ->contains(fn (string $key): bool => request()->query->has($key));
    }

    private static function clean(mixed $value): mixed
    {
        if ($value instanceof DateTimeInterface) {
            return $value->format(DATE_ATOM);
        }

        if (! is_array($value)) {
            return $value;
        }

        $isList = array_is_list($value);
        $cleaned = [];

        foreach ($value as $key => $item) {
            $cleanItem = self::clean($item);

            if ($cleanItem === null || $cleanItem === '' || (is_array($cleanItem) && $cleanItem === [])) {
                continue;
            }

            $cleaned[$key] = $cleanItem;
        }

        return $isList ? array_values($cleaned) : $cleaned;
    }
}
