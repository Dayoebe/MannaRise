<?php

namespace App\Services\ResourceHub\Providers;

use App\Services\ResourceHub\Contracts\ContentProviderInterface;

class OpenLibraryProvider extends AbstractContentProvider implements ContentProviderInterface
{
    public function name(): string
    {
        return 'Open Library';
    }

    public function search(string $query, array $options = []): array
    {
        $baseUrl = rtrim(config('resourcehub.providers.open_library.base_url'), '/');
        $limit = (int) ($options['limit'] ?? 10);

        return $this->cached('resourcehub:open-library:'.md5($query.$limit), function () use ($baseUrl, $query, $limit): array {
            $response = $this->http()->get($baseUrl.'/search.json', [
                'q' => $query,
                'language' => 'eng',
                'limit' => $limit,
            ]);

            if (! $response->ok()) {
                return [];
            }

            return collect($response->json('docs', []))
                ->map(function (array $book) use ($baseUrl): array {
                    $key = $book['key'] ?? null;
                    $cover = isset($book['cover_i']) ? "https://covers.openlibrary.org/b/id/{$book['cover_i']}-L.jpg" : null;
                    $firstSentence = $book['first_sentence'] ?? null;

                    if (is_array($firstSentence)) {
                        $firstSentence = $firstSentence[0] ?? null;
                    }

                    return $this->item([
                        'title' => $this->clean($book['title'] ?? 'Open Library book', 255),
                        'excerpt' => $this->clean(collect($book['subject'] ?? [])->take(5)->join(', '), 220),
                        'description' => $this->clean($firstSentence, 500),
                        'type' => 'book',
                        'source_name' => $this->name(),
                        'source_url' => $key ? $baseUrl.$key : null,
                        'external_id' => $key ? ltrim($key, '/') : null,
                        'author' => collect($book['author_name'] ?? [])->take(3)->join(', ') ?: null,
                        'thumbnail_url' => $cover,
                        'language' => 'en',
                        'license' => 'Open Library metadata',
                        'tags' => $book['subject'] ?? [],
                        'metadata' => ['first_publish_year' => $book['first_publish_year'] ?? null],
                    ]);
                })
                ->filter(fn (array $item) => filled($item['external_id']))
                ->values()
                ->all();
        });
    }
}
