<?php

namespace App\Services\ResourceHub\Providers;

use App\Services\ResourceHub\Contracts\ContentProviderInterface;
use Illuminate\Support\Arr;

class InternetArchiveProvider extends AbstractContentProvider implements ContentProviderInterface
{
    public function name(): string
    {
        return 'Internet Archive';
    }

    public function search(string $query, array $options = []): array
    {
        $baseUrl = rtrim(config('resourcehub.providers.internet_archive.base_url'), '/');
        $limit = (int) ($options['limit'] ?? 10);
        $mediaType = $options['mediatype'] ?? null;
        $terms = trim($query);
        $archiveQuery = $terms !== '' ? $terms : 'christian';

        if ($mediaType) {
            $archiveQuery .= " AND mediatype:{$mediaType}";
        }

        return $this->cached('resourcehub:internet-archive:'.md5($archiveQuery.$limit), function () use ($baseUrl, $archiveQuery, $limit): array {
            $response = $this->http()->get($baseUrl.'/advancedsearch.php', [
                'q' => $archiveQuery,
                'fl[]' => ['identifier', 'title', 'creator', 'description', 'mediatype', 'licenseurl', 'language'],
                'rows' => $limit,
                'page' => 1,
                'output' => 'json',
            ]);

            if (! $response->ok()) {
                return [];
            }

            return collect($response->json('response.docs', []))
                ->map(function (array $doc) use ($baseUrl): array {
                    $identifier = $doc['identifier'] ?? null;
                    $mediaType = $doc['mediatype'] ?? null;
                    $type = match ($mediaType) {
                        'audio' => 'audio',
                        'movies' => 'video',
                        'texts' => 'book',
                        default => 'article',
                    };

                    $creator = Arr::first((array) ($doc['creator'] ?? [])) ?: null;
                    $language = Arr::first((array) ($doc['language'] ?? [])) ?: 'en';

                    return $this->item([
                        'title' => $this->clean($doc['title'] ?? 'Internet Archive resource', 255),
                        'excerpt' => $this->clean($doc['description'] ?? null, 220),
                        'description' => $this->clean($doc['description'] ?? null, 1000),
                        'type' => $type,
                        'source_name' => $this->name(),
                        'source_url' => $identifier ? "{$baseUrl}/details/{$identifier}" : null,
                        'external_id' => $identifier,
                        'author' => is_string($creator) ? $creator : null,
                        'thumbnail_url' => $identifier ? "{$baseUrl}/services/img/{$identifier}" : null,
                        'language' => is_string($language) ? $language : 'en',
                        'license' => Arr::first((array) ($doc['licenseurl'] ?? [])) ?: 'See Internet Archive item',
                        'tags' => ['internet archive', $type],
                        'metadata' => ['mediatype' => $mediaType],
                    ]);
                })
                ->filter(fn (array $item) => filled($item['external_id']))
                ->values()
                ->all();
        });
    }
}
