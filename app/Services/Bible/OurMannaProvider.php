<?php

namespace App\Services\Bible;

use App\Models\PlatformSetting;
use Illuminate\Support\Facades\Http;

class OurMannaProvider implements BibleProviderInterface
{
    public function name(): string
    {
        return 'our_manna';
    }

    public function isConfigured(): bool
    {
        return (bool) PlatformSetting::value('bible.our_manna_enabled') && (bool) config('bible.providers.our_manna.enabled');
    }

    public function getDailyVerse(?string $translation = null): ?BibleVerseData
    {
        $response = Http::timeout(8)
            ->retry(2, 200)
            ->get(rtrim((string) config('bible.providers.our_manna.base_url'), '/').'/get', [
                'format' => 'json',
                'order' => 'daily',
            ]);

        if (! $response->successful()) {
            return null;
        }

        return $this->normalizeResponse($response->json(), $translation);
    }

    public function getRandomVerse(?string $translation = null): ?BibleVerseData
    {
        return $this->getDailyVerse($translation);
    }

    public function getPassage(string $reference, ?string $translation = null): ?BibleVerseData
    {
        return null;
    }

    public function normalizeResponse(array $payload, ?string $translation = null): ?BibleVerseData
    {
        $details = $payload['verse']['details'] ?? null;

        if (! is_array($details) || empty($details['text']) || empty($details['reference'])) {
            return null;
        }

        return new BibleVerseData(
            provider: $this->name(),
            reference: (string) $details['reference'],
            text: trim((string) $details['text']),
            translation: $details['version'] ?? $translation,
            book: $details['bookname'] ?? null,
            chapter: isset($details['chapter']) ? (int) $details['chapter'] : null,
            verse: isset($details['verse']) ? (string) $details['verse'] : null,
            payload: $payload,
        );
    }
}
