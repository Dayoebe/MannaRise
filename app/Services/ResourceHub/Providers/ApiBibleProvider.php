<?php

namespace App\Services\ResourceHub\Providers;

use App\Services\ResourceHub\Contracts\ContentProviderInterface;
use Illuminate\Support\Str;

class ApiBibleProvider extends AbstractContentProvider implements ContentProviderInterface
{
    public function name(): string
    {
        return 'API.Bible';
    }

    public function search(string $query, array $options = []): array
    {
        $key = config('resourcehub.providers.api_bible.key');
        $bibleId = $options['bible_id'] ?? config('resourcehub.providers.api_bible.bible_id');

        if (! $key || ! $bibleId) {
            return [];
        }

        $baseUrl = rtrim(config('resourcehub.providers.api_bible.base_url'), '/');
        $search = trim($query ?: 'faith');

        return $this->cached('resourcehub:api-bible:'.md5($bibleId.$search), function () use ($baseUrl, $key, $bibleId, $search): array {
            $response = $this->http()
                ->withHeader('api-key', $key)
                ->get("{$baseUrl}/bibles/{$bibleId}/search", [
                    'query' => $search,
                    'limit' => 10,
                    'sort' => 'relevance',
                ]);

            if (! $response->ok()) {
                return [];
            }

            return collect($response->json('data.verses', []))
                ->map(function (array $verse) use ($baseUrl, $bibleId): array {
                    $reference = $verse['reference'] ?? 'Bible verse';
                    $text = $this->clean($verse['text'] ?? '', 1500);
                    $id = $verse['id'] ?? Str::slug($reference);

                    return $this->item([
                        'title' => $reference,
                        'excerpt' => Str::limit($text ?: '', 180),
                        'content' => $text,
                        'type' => 'bible',
                        'source_name' => $this->name(),
                        'source_url' => "{$baseUrl}/bibles/{$bibleId}/verses/{$id}",
                        'external_id' => $id,
                        'license' => 'API.Bible provider terms',
                        'metadata' => ['reference' => $reference, 'bible_id' => $bibleId],
                    ]);
                })
                ->filter(fn (array $item) => filled($item['content']))
                ->values()
                ->all();
        });
    }
}
