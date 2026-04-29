<?php

namespace App\Http\Controllers;

use App\Models\Devotional;
use App\Models\SpiritualBook;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Route;

class SeoController extends Controller
{
    public function robots(): Response
    {
        $lines = [
            'User-agent: *',
            app()->environment('production') ? 'Allow: /' : 'Disallow: /',
            'Disallow: /admin',
            'Disallow: /dashboard',
            'Disallow: /journal',
            'Disallow: /favorites',
            'Disallow: /growth-path',
            'Disallow: /reminders',
            'Disallow: /groups/invite',
            'Sitemap: '.route('seo.sitemap'),
            '',
        ];

        return response(implode("\n", $lines), 200, [
            'Content-Type' => 'text/plain; charset=UTF-8',
        ]);
    }

    public function sitemap(): Response
    {
        $urls = collect(config('seo.sitemap.static', []))
            ->filter(fn (array $item) => Route::has($item['route']))
            ->map(fn (array $item) => [
                'loc' => route($item['route']),
                'lastmod' => now()->toAtomString(),
                'changefreq' => $item['changefreq'] ?? 'weekly',
                'priority' => $item['priority'] ?? '0.7',
            ]);

        $devotionalUrls = Devotional::query()
            ->published()
            ->latest('published_at')
            ->get(['slug', 'updated_at', 'published_at'])
            ->map(fn (Devotional $devotional) => [
                'loc' => route('devotionals.show', $devotional->slug),
                'lastmod' => ($devotional->updated_at ?? $devotional->published_at ?? now())->toAtomString(),
                'changefreq' => 'weekly',
                'priority' => '0.8',
            ]);

        $libraryUrls = SpiritualBook::query()
            ->latest('updated_at')
            ->get(['slug', 'updated_at'])
            ->map(fn (SpiritualBook $book) => [
                'loc' => route('library.show', $book->slug),
                'lastmod' => ($book->updated_at ?? now())->toAtomString(),
                'changefreq' => 'monthly',
                'priority' => '0.7',
            ]);

        $xml = view('seo.sitemap', [
            'urls' => $urls
                ->merge($devotionalUrls)
                ->merge($libraryUrls)
                ->unique('loc')
                ->values(),
        ])->render();

        return response($xml, 200, [
            'Content-Type' => 'application/xml; charset=UTF-8',
        ]);
    }
}
