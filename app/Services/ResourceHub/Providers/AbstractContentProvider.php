<?php

namespace App\Services\ResourceHub\Providers;

use Illuminate\Http\Client\PendingRequest;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;
use Throwable;

abstract class AbstractContentProvider
{
    protected function http(): PendingRequest
    {
        return Http::timeout(8)
            ->retry(2, 250)
            ->acceptJson();
    }

    /**
     * @return array<int, array<string, mixed>>
     */
    protected function cached(string $key, callable $callback): array
    {
        try {
            return Cache::remember($key, config('resourcehub.cache_ttl', 86400), fn () => $callback());
        } catch (Throwable) {
            return [];
        }
    }

    protected function clean(?string $value, int $limit = 500): ?string
    {
        $value = Str::of(strip_tags((string) $value))->squish()->toString();

        return $value !== '' ? Str::limit($value, $limit, '') : null;
    }

    protected function item(array $data): array
    {
        return array_merge([
            'title' => 'Untitled resource',
            'excerpt' => null,
            'description' => null,
            'content' => null,
            'type' => 'article',
            'source_name' => $this->name(),
            'source_url' => null,
            'external_id' => null,
            'author' => null,
            'thumbnail_url' => null,
            'media_url' => null,
            'embed_url' => null,
            'language' => 'en',
            'license' => null,
            'tags' => [],
            'metadata' => [],
            'is_published' => true,
            'published_at' => now(),
        ], $data);
    }
}
