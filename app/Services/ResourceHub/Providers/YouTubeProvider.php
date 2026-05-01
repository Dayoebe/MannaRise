<?php

namespace App\Services\ResourceHub\Providers;

use App\Services\ResourceHub\Contracts\ContentProviderInterface;

class YouTubeProvider extends AbstractContentProvider implements ContentProviderInterface
{
    public function name(): string
    {
        return 'YouTube';
    }

    public function search(string $query, array $options = []): array
    {
        $key = config('resourcehub.providers.youtube.key');

        if (! $key) {
            return [];
        }

        $baseUrl = rtrim(config('resourcehub.providers.youtube.base_url'), '/');
        $limit = (int) ($options['limit'] ?? 10);

        return $this->cached('resourcehub:youtube:'.md5($query.$limit), function () use ($baseUrl, $key, $query, $limit): array {
            $response = $this->http()->get($baseUrl.'/search', [
                'part' => 'snippet',
                'q' => $query,
                'type' => 'video',
                'safeSearch' => 'strict',
                'maxResults' => min(25, $limit),
                'key' => $key,
            ]);

            if (! $response->ok()) {
                return [];
            }

            return collect($response->json('items', []))
                ->map(function (array $video): array {
                    $videoId = $video['id']['videoId'] ?? null;
                    $snippet = $video['snippet'] ?? [];

                    return $this->item([
                        'title' => $this->clean($snippet['title'] ?? 'YouTube video', 255),
                        'excerpt' => $this->clean($snippet['description'] ?? null, 220),
                        'description' => $this->clean($snippet['description'] ?? null, 1000),
                        'type' => 'video',
                        'source_name' => $this->name(),
                        'source_url' => $videoId ? "https://www.youtube.com/watch?v={$videoId}" : null,
                        'external_id' => $videoId,
                        'author' => $snippet['channelTitle'] ?? null,
                        'thumbnail_url' => $snippet['thumbnails']['high']['url'] ?? $snippet['thumbnails']['default']['url'] ?? null,
                        'embed_url' => $videoId ? "https://www.youtube.com/embed/{$videoId}" : null,
                        'license' => 'YouTube embed',
                        'tags' => ['video', 'youtube'],
                        'metadata' => ['published_at' => $snippet['publishedAt'] ?? null],
                    ]);
                })
                ->filter(fn (array $item) => filled($item['external_id']))
                ->values()
                ->all();
        });
    }
}
