<?php

namespace App\Http\Controllers;

use App\Models\DailyDevotion;
use App\Models\Devotional;
use App\Models\PrayerRoom;
use App\Models\ResourceItem;
use App\Models\SpiritualBook;
use App\Support\DevotionalPlans;
use App\Support\LanguagePages;
use App\Support\Seo;
use Illuminate\Http\Response;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Str;

class SeoController extends Controller
{
    public function robots(): Response
    {
        $allowTraining = (bool) config('seo.ai_crawlers.allow_training');
        $lines = [
            '# MannaRise robots.txt',
            '# Public pages and rendering assets are open for search and AI discovery crawlers.',
            '# Training crawlers are controlled separately with SEO_ALLOW_AI_TRAINING_CRAWLERS.',
            '',
            'User-agent: Googlebot',
            'Allow: /',
            '',
            'User-agent: Bingbot',
            'Allow: /',
            '',
            'User-agent: OAI-SearchBot',
            'Allow: /',
            '',
            'User-agent: ChatGPT-User',
            'Allow: /',
            '',
            'User-agent: PerplexityBot',
            'Allow: /',
            '',
            '# Configurable AI training crawler policy.',
            'User-agent: GPTBot',
            $allowTraining ? 'Allow: /' : 'Disallow: /',
            '',
            'User-agent: *',
            'Allow: /',
            'Allow: /build/',
            'Allow: /icons/',
            'Allow: /manifest.webmanifest',
            'Allow: /feed.xml',
            'Allow: /feed.atom',
            'Allow: /llms.txt',
            'Allow: /llms-full.txt',
            'Disallow: /admin',
            'Disallow: /dashboard',
            'Disallow: /onboarding',
            'Disallow: /journal',
            'Disallow: /favorites',
            'Disallow: /growth-path',
            'Disallow: /reminders',
            'Disallow: /offline-library',
            'Disallow: /groups',
            'Disallow: /groups/invite',
            'Disallow: /mail/notifications',
            'Disallow: /login',
            'Disallow: /register',
            'Disallow: /livewire-*/preview-file',
            'Disallow: /*?q=',
            'Disallow: /*?search=',
            'Disallow: /*?type=',
            'Disallow: /*?category=',
            'Disallow: /*?status=',
            'Disallow: /*?page=',
            '',
            'Sitemap: '.route('seo.sitemap'),
            '',
        ];

        return $this->text(implode("\n", $lines));
    }

    public function sitemap(): Response
    {
        $sitemaps = collect([
            ['loc' => route('seo.sitemap.pages'), 'lastmod' => $this->latestTimestamp()],
            ['loc' => route('seo.sitemap.devotionals'), 'lastmod' => $this->latestDevotionalTimestamp()],
            ['loc' => route('seo.sitemap.resources'), 'lastmod' => $this->latestResourceTimestamp()],
            ['loc' => route('seo.sitemap.library'), 'lastmod' => $this->latestLibraryTimestamp()],
            ['loc' => route('seo.sitemap.images'), 'lastmod' => $this->latestResourceTimestamp()],
        ]);

        return $this->xml(view('seo.sitemap-index', ['sitemaps' => $sitemaps])->render());
    }

    public function sitemapPages(): Response
    {
        return $this->urlset($this->pageUrls());
    }

    public function sitemapDevotionals(): Response
    {
        return $this->urlset($this->devotionalUrls());
    }

    public function sitemapResources(): Response
    {
        return $this->urlset($this->resourceUrls());
    }

    public function sitemapLibrary(): Response
    {
        return $this->urlset($this->libraryUrls());
    }

    public function sitemapImages(): Response
    {
        $urls = collect([
            [
                'loc' => route('home'),
                'lastmod' => $this->latestTimestamp(),
                'images' => [
                    [
                        'loc' => Seo::absoluteUrl((string) config('seo.image')),
                        'title' => config('seo.site_name').' social preview image',
                    ],
                ],
            ],
        ]);

        $resourceImages = ResourceItem::query()
            ->published()
            ->whereNotNull('thumbnail_url')
            ->latest('updated_at')
            ->get(['slug', 'title', 'thumbnail_url', 'updated_at'])
            ->map(fn (ResourceItem $resource): array => [
                'loc' => route('resources.show', $resource->slug),
                'lastmod' => ($resource->updated_at ?? now())->toAtomString(),
                'images' => [
                    [
                        'loc' => Seo::absoluteUrl($resource->thumbnail_url),
                        'title' => $resource->title,
                    ],
                ],
            ]);

        return $this->xml(view('seo.sitemap-images', [
            'urls' => $urls->merge($resourceImages)->values(),
        ])->render());
    }

    public function feed(): Response
    {
        return $this->xml(view('seo.feed-rss', [
            'items' => $this->feedItems(),
            'updatedAt' => $this->latestTimestamp(),
        ])->render(), 'application/rss+xml; charset=UTF-8');
    }

    public function atom(): Response
    {
        return $this->xml(view('seo.feed-atom', [
            'items' => $this->feedItems(),
            'updatedAt' => $this->latestTimestamp(),
        ])->render(), 'application/atom+xml; charset=UTF-8');
    }

    public function llms(): Response
    {
        return $this->markdown('seo.llms', $this->agentFileData(compact: true));
    }

    public function llmsFull(): Response
    {
        return $this->markdown('seo.llms-full', $this->agentFileData(compact: false));
    }

    public function ai(): Response
    {
        return $this->markdown('seo.ai', $this->agentFileData(compact: true));
    }

    /**
     * @return Collection<int, array<string, string>>
     */
    private function pageUrls(): Collection
    {
        $static = collect(config('seo.sitemap.static', []))
            ->filter(fn (array $item): bool => Route::has($item['route']))
            ->map(fn (array $item): array => [
                'loc' => route($item['route']),
                'lastmod' => $this->latestTimestamp(),
                'changefreq' => $item['changefreq'] ?? 'weekly',
                'priority' => $item['priority'] ?? '0.7',
                'alternates' => $item['route'] === 'home' ? LanguagePages::homeAlternates() : [],
            ]);

        $localizedHomes = collect(LanguagePages::codes())
            ->map(fn (string $locale): array => [
                'loc' => route('localized.home', ['locale' => $locale]),
                'lastmod' => $this->latestTimestamp(),
                'changefreq' => 'daily',
                'priority' => $locale === 'en' ? '0.95' : '0.85',
                'alternates' => LanguagePages::homeAlternates(),
            ]);

        $plans = collect(DevotionalPlans::all())
            ->map(fn (array $plan): array => [
                'loc' => route('devotional-plans.show', $plan['slug']),
                'lastmod' => $this->latestDevotionalTimestamp(),
                'changefreq' => 'weekly',
                'priority' => '0.7',
            ]);

        $rooms = $this->activePrayerRooms()
            ->map(fn (array $room): array => [
                'loc' => route('prayer-rooms.show', $room['slug']),
                'lastmod' => $this->latestTimestamp(),
                'changefreq' => 'daily',
                'priority' => '0.6',
            ]);

        $dailyPermalinks = collect(range(0, 14))
            ->map(fn (int $daysAgo): Carbon => Carbon::today()->subDays($daysAgo))
            ->flatMap(function (Carbon $date): Collection {
                $lastmod = $date->isToday() ? $this->latestTimestamp() : $date->copy()->endOfDay()->toAtomString();
                $alternates = LanguagePages::dailyAlternates($date);
                $entries = collect([[
                    'loc' => route('daily.show', ['date' => $date->toDateString()]),
                    'lastmod' => $lastmod,
                    'changefreq' => $date->isToday() ? 'daily' : 'weekly',
                    'priority' => $date->isToday() ? '0.9' : '0.6',
                    'alternates' => $alternates,
                ]]);

                return $entries->merge(collect(LanguagePages::codes())->map(fn (string $locale): array => [
                    'loc' => route('daily.localized.show', ['locale' => $locale, 'date' => $date->toDateString()]),
                    'lastmod' => $lastmod,
                    'changefreq' => $date->isToday() ? 'daily' : 'weekly',
                    'priority' => $date->isToday() ? '0.85' : '0.55',
                    'alternates' => $alternates,
                ]));
            });

        return $static
            ->merge($localizedHomes)
            ->merge($dailyPermalinks)
            ->merge($plans)
            ->merge($rooms)
            ->unique('loc')
            ->values();
    }

    /**
     * @return Collection<int, array<string, string>>
     */
    private function devotionalUrls(): Collection
    {
        return Devotional::query()
            ->published()
            ->latest('published_at')
            ->get(['slug', 'updated_at', 'published_at'])
            ->map(fn (Devotional $devotional): array => [
                'loc' => route('devotionals.show', $devotional->slug),
                'lastmod' => ($devotional->updated_at ?? $devotional->published_at ?? now())->toAtomString(),
                'changefreq' => 'weekly',
                'priority' => '0.8',
            ]);
    }

    /**
     * @return Collection<int, array<string, string>>
     */
    private function resourceUrls(): Collection
    {
        $resources = ResourceItem::query()
            ->published()
            ->latest('published_at')
            ->get(['slug', 'updated_at', 'published_at'])
            ->map(fn (ResourceItem $resource): array => [
                'loc' => route('resources.show', $resource->slug),
                'lastmod' => ($resource->updated_at ?? $resource->published_at ?? now())->toAtomString(),
                'changefreq' => 'weekly',
                'priority' => '0.7',
            ]);

        $dailyDevotions = DailyDevotion::query()
            ->published()
            ->latest('devotion_date')
            ->get(['slug', 'updated_at', 'devotion_date'])
            ->map(fn (DailyDevotion $devotion): array => [
                'loc' => route('resources.devotion.show', $devotion->slug),
                'lastmod' => ($devotion->updated_at ?? $devotion->devotion_date ?? now())->toAtomString(),
                'changefreq' => 'daily',
                'priority' => '0.75',
            ]);

        return $resources->merge($dailyDevotions)->unique('loc')->values();
    }

    /**
     * @return Collection<int, array<string, string>>
     */
    private function libraryUrls(): Collection
    {
        return SpiritualBook::query()
            ->latest('updated_at')
            ->get(['slug', 'updated_at'])
            ->map(fn (SpiritualBook $book): array => [
                'loc' => route('library.show', $book->slug),
                'lastmod' => ($book->updated_at ?? now())->toAtomString(),
                'changefreq' => 'monthly',
                'priority' => '0.7',
            ]);
    }

    /**
     * @return Collection<int, array<string, mixed>>
     */
    private function feedItems(): Collection
    {
        $devotionals = Devotional::query()
            ->with('category')
            ->published()
            ->latest('published_at')
            ->take(25)
            ->get()
            ->map(fn (Devotional $devotional): array => [
                'title' => $devotional->title,
                'url' => route('devotionals.show', $devotional->slug),
                'summary' => $this->summary($devotional->content),
                'published_at' => $devotional->published_at ?? $devotional->created_at,
                'updated_at' => $devotional->updated_at ?? $devotional->published_at,
                'category' => $devotional->category?->name ?: 'Devotional',
            ]);

        $resources = ResourceItem::query()
            ->with('category')
            ->published()
            ->latest('published_at')
            ->take(15)
            ->get()
            ->map(fn (ResourceItem $resource): array => [
                'title' => $resource->title,
                'url' => route('resources.show', $resource->slug),
                'summary' => $this->summary($resource->excerpt ?: $resource->description ?: $resource->content),
                'published_at' => $resource->published_at ?? $resource->created_at,
                'updated_at' => $resource->updated_at ?? $resource->published_at,
                'category' => $resource->category?->name ?: Str::headline($resource->type),
            ]);

        $dailyDevotions = DailyDevotion::query()
            ->published()
            ->latest('devotion_date')
            ->take(10)
            ->get()
            ->map(fn (DailyDevotion $devotion): array => [
                'title' => $devotion->title,
                'url' => route('resources.devotion.show', $devotion->slug),
                'summary' => $this->summary($devotion->devotion_text),
                'published_at' => $devotion->devotion_date,
                'updated_at' => $devotion->updated_at ?? $devotion->devotion_date,
                'category' => 'Daily Devotion',
            ]);

        return $devotionals
            ->merge($resources)
            ->merge($dailyDevotions)
            ->sortByDesc(fn (array $item) => $item['published_at'])
            ->take(40)
            ->values();
    }

    /**
     * @return array<string, mixed>
     */
    private function agentFileData(bool $compact): array
    {
        $corePages = collect([
            ['label' => 'Homepage', 'url' => route('home')],
            ['label' => 'About', 'url' => route('about')],
            ['label' => 'Contact', 'url' => route('contact')],
            ['label' => 'Daily Spiritual Rhythm', 'url' => route('daily.index')],
            ['label' => 'Devotionals', 'url' => route('devotionals.index')],
            ['label' => 'Bible Reader', 'url' => route('bible')],
            ['label' => 'Resource Hub', 'url' => route('resources.index')],
            ['label' => 'Prayer Rooms', 'url' => route('prayer-rooms.index')],
            ['label' => 'Testimonies', 'url' => route('testimonies.index')],
        ]);

        $topics = collect([
            'Daily Christian devotionals',
            'Bible study',
            'Guided prayer',
            'Prayer requests',
            'Christian testimonies',
            'Memory verses',
            'Devotional plans',
            'Spiritual journaling',
            'Christian books and resources',
            'Audio devotionals',
        ])
            ->merge(Devotional::query()
                ->published()
                ->with('category')
                ->latest('published_at')
                ->take($compact ? 6 : 14)
                ->get()
                ->pluck('category.name')
                ->filter())
            ->unique()
            ->take($compact ? 12 : 20)
            ->values();

        return [
            'siteName' => config('seo.site_name'),
            'description' => config('seo.description'),
            'corePages' => $corePages,
            'topics' => $topics,
            'latestItems' => $this->feedItems()->take($compact ? 6 : 15),
            'contactEmail' => config('seo.contact.email'),
            'sitemapUrl' => route('seo.sitemap'),
            'feedUrl' => route('seo.feed'),
            'atomUrl' => route('seo.feed.atom'),
            'llmsFullUrl' => route('seo.llms-full'),
        ];
    }

    /**
     * @return Collection<int, array<string, mixed>>
     */
    private function activePrayerRooms(): Collection
    {
        $rooms = PrayerRoom::query()
            ->where('is_active', true)
            ->orderBy('sort_order')
            ->get(['slug', 'name', 'description'])
            ->map(fn (PrayerRoom $room): array => $room->only(['slug', 'name', 'description']));

        return $rooms->isNotEmpty() ? $rooms : collect(PrayerRoom::defaults());
    }

    /**
     * @param  Collection<int, array<string, string>>  $urls
     */
    private function urlset(Collection $urls): Response
    {
        return $this->xml(view('seo.sitemap', [
            'urls' => $urls->unique('loc')->values(),
        ])->render());
    }

    private function text(string $content): Response
    {
        return response($content, 200, [
            'Content-Type' => 'text/plain; charset=UTF-8',
        ]);
    }

    private function markdown(string $view, array $data): Response
    {
        return response(view($view, $data)->render(), 200, [
            'Content-Type' => 'text/markdown; charset=UTF-8',
        ]);
    }

    private function xml(string $content, string $contentType = 'application/xml; charset=UTF-8'): Response
    {
        return response($content, 200, [
            'Content-Type' => $contentType,
        ]);
    }

    private function summary(?string $value): string
    {
        return Str::limit(Str::of(strip_tags((string) $value))->replaceMatches('/\s+/', ' ')->trim()->toString(), 300, '');
    }

    private function latestTimestamp(): string
    {
        return now()->toAtomString();
    }

    private function latestDevotionalTimestamp(): string
    {
        return $this->atomTimestamp(Devotional::query()->published()->max('updated_at'))
            ?? $this->latestTimestamp();
    }

    private function latestResourceTimestamp(): string
    {
        $resource = ResourceItem::query()->published()->max('updated_at');
        $daily = DailyDevotion::query()->published()->max('updated_at');

        $latest = collect([$resource, $daily])
            ->filter()
            ->max();

        return $this->atomTimestamp($latest) ?? $this->latestTimestamp();
    }

    private function latestLibraryTimestamp(): string
    {
        return $this->atomTimestamp(SpiritualBook::query()->max('updated_at'))
            ?? $this->latestTimestamp();
    }

    private function atomTimestamp(mixed $value): ?string
    {
        if (! $value) {
            return null;
        }

        return Carbon::parse($value)->toAtomString();
    }
}
