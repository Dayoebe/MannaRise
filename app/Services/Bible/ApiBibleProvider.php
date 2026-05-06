<?php

namespace App\Services\Bible;

use App\Models\PlatformSetting;
use Illuminate\Support\Facades\Http;

class ApiBibleProvider implements BibleProviderInterface
{
    public function name(): string
    {
        return 'api_bible';
    }

    public function isConfigured(): bool
    {
        return (bool) PlatformSetting::value('bible.api_bible_enabled')
            && filled(config('bible.providers.api_bible.key'))
            && filled(config('bible.providers.api_bible.bible_id'));
    }

    public function getDailyVerse(?string $translation = null): ?BibleVerseData
    {
        return $this->getRandomVerse($translation);
    }

    public function getRandomVerse(?string $translation = null): ?BibleVerseData
    {
        return $this->getPassage('JHN.3.16', $translation);
    }

    public function getPassage(string $reference, ?string $translation = null): ?BibleVerseData
    {
        if (! $this->isConfigured()) {
            return null;
        }

        $response = Http::withHeaders(['api-key' => config('bible.providers.api_bible.key')])
            ->timeout(8)
            ->retry(2, 200)
            ->get(sprintf(
                '%s/bibles/%s/passages/%s',
                rtrim((string) config('bible.providers.api_bible.base_url'), '/'),
                config('bible.providers.api_bible.bible_id'),
                urlencode($reference),
            ), [
                'content-type' => 'text',
                'include-notes' => 'false',
                'include-titles' => 'false',
                'include-chapter-numbers' => 'false',
                'include-verse-numbers' => 'false',
                'include-verse-spans' => 'false',
            ]);

        if (! $response->successful()) {
            return null;
        }

        return $this->normalizeResponse($response->json(), $translation);
    }

    public function normalizeResponse(array $payload, ?string $translation = null): ?BibleVerseData
    {
        $data = $payload['data'] ?? null;

        if (! is_array($data) || empty($data['content']) || empty($data['reference'])) {
            return null;
        }

        return new BibleVerseData(
            provider: $this->name(),
            reference: (string) $data['reference'],
            text: trim(strip_tags((string) $data['content'])),
            translation: $translation,
            book: $this->bookFromReference((string) $data['reference']),
            chapter: null,
            verse: null,
            payload: $payload,
        );
    }

    private function bookFromReference(string $reference): ?string
    {
        preg_match('/^(.+?)\s+\d/', $reference, $matches);

        return isset($matches[1]) ? trim($matches[1]) : null;
    }
}
