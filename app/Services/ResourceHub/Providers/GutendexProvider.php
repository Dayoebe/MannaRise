<?php

namespace App\Services\ResourceHub\Providers;

use App\Services\ResourceHub\Contracts\ContentProviderInterface;
use Illuminate\Support\Arr;

class GutendexProvider extends AbstractContentProvider implements ContentProviderInterface
{
    public function name(): string
    {
        return 'Gutendex';
    }

    public function search(string $query, array $options = []): array
    {
        $baseUrl = rtrim(config('resourcehub.providers.gutendex.base_url'), '/');
        $limit = (int) ($options['limit'] ?? 10);

        return $this->cached('resourcehub:gutendex:'.md5($query.$limit), function () use ($baseUrl, $query, $limit): array {
            $response = $this->http()->get($baseUrl.'/books', [
                'search' => $query,
                'languages' => 'en',
            ]);

            if (! $response->ok()) {
                return [];
            }

            return collect($response->json('results', []))
                ->take($limit)
                ->map(function (array $book): array {
                    $formats = $book['formats'] ?? [];
                    $authors = collect($book['authors'] ?? [])->pluck('name')->filter()->join(', ');
                    $sourceUrl = Arr::get($formats, 'text/html') ?: Arr::get($formats, 'application/epub+zip') ?: Arr::first($formats);

                    return $this->item([
                        'title' => $this->clean($book['title'] ?? 'Project Gutenberg book', 255),
                        'excerpt' => $authors ? "Public-domain book by {$authors}." : 'Public-domain book from Project Gutenberg.',
                        'description' => $authors ? "Public-domain book by {$authors}." : null,
                        'type' => 'book',
                        'source_name' => $this->name(),
                        'source_url' => is_string($sourceUrl) ? $sourceUrl : null,
                        'external_id' => (string) ($book['id'] ?? ''),
                        'author' => $authors ?: null,
                        'thumbnail_url' => Arr::get($formats, 'image/jpeg'),
                        'language' => collect($book['languages'] ?? ['en'])->first() ?: 'en',
                        'license' => 'Project Gutenberg public domain',
                        'tags' => $book['subjects'] ?? [],
                        'metadata' => ['formats' => $formats, 'download_count' => $book['download_count'] ?? null],
                    ]);
                })
                ->filter(fn (array $item) => $item['external_id'] !== '')
                ->values()
                ->all();
        });
    }
}
