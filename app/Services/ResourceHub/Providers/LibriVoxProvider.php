<?php

namespace App\Services\ResourceHub\Providers;

use App\Services\ResourceHub\Contracts\ContentProviderInterface;

class LibriVoxProvider extends AbstractContentProvider implements ContentProviderInterface
{
    public function name(): string
    {
        return 'LibriVox';
    }

    public function search(string $query, array $options = []): array
    {
        $baseUrl = rtrim(config('resourcehub.providers.librivox.base_url'), '/');
        $limit = (int) ($options['limit'] ?? 10);

        return $this->cached('resourcehub:librivox:'.md5($query.$limit), function () use ($baseUrl, $query, $limit): array {
            $response = $this->http()->get($baseUrl.'/audiobooks', [
                'format' => 'json',
                'extended' => 1,
                'title' => $query,
                'limit' => $limit,
            ]);

            if (! $response->ok()) {
                return [];
            }

            return collect($response->json('books', []))
                ->map(function (array $book): array {
                    $authors = collect($book['authors'] ?? [])
                        ->map(fn (array $author) => trim(($author['first_name'] ?? '').' '.($author['last_name'] ?? '')))
                        ->filter()
                        ->join(', ');

                    return $this->item([
                        'title' => $this->clean($book['title'] ?? 'LibriVox audiobook', 255),
                        'excerpt' => $this->clean($book['description'] ?? 'Public-domain audiobook from LibriVox.', 220),
                        'description' => $this->clean($book['description'] ?? null, 1000),
                        'type' => 'audio',
                        'source_name' => $this->name(),
                        'source_url' => $book['url_librivox'] ?? null,
                        'external_id' => (string) ($book['id'] ?? ''),
                        'author' => $authors ?: null,
                        'media_url' => $book['url_zip_file'] ?? null,
                        'language' => $book['language'] ?? 'en',
                        'license' => 'Public domain',
                        'tags' => ['audiobook', 'public domain'],
                        'metadata' => ['duration' => $book['totaltimesecs'] ?? null],
                    ]);
                })
                ->filter(fn (array $item) => $item['external_id'] !== '')
                ->values()
                ->all();
        });
    }
}
