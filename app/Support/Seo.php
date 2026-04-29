<?php

namespace App\Support;

use Illuminate\Support\Str;

class Seo
{
    /**
     * @param  array<string, mixed>  $overrides
     * @return array<string, mixed>
     */
    public static function meta(array $overrides = []): array
    {
        $title = trim((string) ($overrides['title'] ?? config('seo.title')));
        $description = trim((string) ($overrides['description'] ?? config('seo.description')));
        $siteName = trim((string) config('seo.site_name'));
        $type = $overrides['type'] ?? 'website';
        $canonical = $overrides['canonical'] ?? url()->current();
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
            'schema' => $overrides['schema'] ?? self::defaultSchema($canonical),
        ];
    }

    public static function robots(): string
    {
        return app()->environment('production')
            ? (string) config('seo.robots.production')
            : (string) config('seo.robots.non_production');
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
    public static function defaultSchema(string $canonical): array
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
                'name' => config('seo.title'),
                'url' => $canonical,
                'isPartOf' => [
                    '@type' => 'WebSite',
                    'name' => config('seo.site_name'),
                    'url' => $organizationUrl,
                ],
            ],
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
