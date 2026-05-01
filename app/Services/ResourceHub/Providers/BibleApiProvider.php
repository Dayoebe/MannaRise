<?php

namespace App\Services\ResourceHub\Providers;

use App\Services\ResourceHub\Contracts\ContentProviderInterface;
use Illuminate\Support\Str;

class BibleApiProvider extends AbstractContentProvider implements ContentProviderInterface
{
    public function name(): string
    {
        return 'bible-api.com';
    }

    public function search(string $query, array $options = []): array
    {
        $reference = trim($query ?: ($options['reference'] ?? 'John 3:16'));
        $baseUrl = rtrim(config('resourcehub.providers.bible_api.base_url'), '/');

        return $this->cached('resourcehub:bible-api:'.md5($reference), function () use ($baseUrl, $reference): array {
            $response = $this->http()->get($baseUrl.'/'.rawurlencode($reference), ['translation' => 'kjv']);

            if (! $response->ok()) {
                return [];
            }

            $data = $response->json();
            $text = $this->clean($data['text'] ?? '', 2000);
            $reference = $data['reference'] ?? $reference;

            if (! $text) {
                return [];
            }

            return [
                $this->item([
                    'title' => $reference,
                    'excerpt' => Str::limit($text, 180),
                    'content' => $text,
                    'type' => 'bible',
                    'external_id' => Str::slug($reference),
                    'source_url' => $baseUrl.'/'.rawurlencode($reference),
                    'license' => 'Public-domain / provider terms',
                    'metadata' => ['reference' => $reference],
                ]),
            ];
        });
    }
}
