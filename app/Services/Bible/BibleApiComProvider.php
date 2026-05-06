<?php

namespace App\Services\Bible;

use Illuminate\Support\Facades\Http;

class BibleApiComProvider implements BibleProviderInterface
{
    public function name(): string
    {
        return 'bible_api_com';
    }

    public function isConfigured(): bool
    {
        return filled(config('bible.providers.bible_api_com.base_url'));
    }

    public function getDailyVerse(?string $translation = null): ?BibleVerseData
    {
        return $this->getRandomVerse($translation);
    }

    public function getRandomVerse(?string $translation = null): ?BibleVerseData
    {
        $response = Http::timeout(8)
            ->retry(2, 200)
            ->get(rtrim((string) config('bible.providers.bible_api_com.base_url'), '/').'/data/web/random');

        if (! $response->successful()) {
            return null;
        }

        return $this->normalizeResponse($response->json(), $translation ?: 'web');
    }

    public function getPassage(string $reference, ?string $translation = null): ?BibleVerseData
    {
        $translation ??= config('bible.default_translation');
        $response = Http::timeout(8)
            ->retry(2, 200)
            ->get(rtrim((string) config('bible.providers.bible_api_com.base_url'), '/').'/'.urlencode($reference), [
                'translation' => $translation,
            ]);

        if (! $response->successful()) {
            return null;
        }

        return $this->normalizeResponse($response->json(), $translation);
    }

    public function normalizeResponse(array $payload, ?string $translation = null): ?BibleVerseData
    {
        if (isset($payload['random_verse'])) {
            $verse = $payload['random_verse'];

            return new BibleVerseData(
                provider: $this->name(),
                reference: $verse['book'].' '.$verse['chapter'].':'.$verse['verse'],
                text: trim((string) $verse['text']),
                translation: $payload['translation']['identifier'] ?? $translation,
                book: $verse['book'] ?? null,
                chapter: isset($verse['chapter']) ? (int) $verse['chapter'] : null,
                verse: isset($verse['verse']) ? (string) $verse['verse'] : null,
                payload: $payload,
            );
        }

        if (! isset($payload['text'], $payload['reference'])) {
            return null;
        }

        $firstVerse = $payload['verses'][0] ?? [];

        return new BibleVerseData(
            provider: $this->name(),
            reference: (string) $payload['reference'],
            text: trim((string) $payload['text']),
            translation: $payload['translation_id'] ?? $translation,
            book: $firstVerse['book_name'] ?? $this->bookFromReference((string) $payload['reference']),
            chapter: isset($firstVerse['chapter']) ? (int) $firstVerse['chapter'] : $this->chapterFromReference((string) $payload['reference']),
            verse: isset($firstVerse['verse']) ? (string) $firstVerse['verse'] : $this->verseFromReference((string) $payload['reference']),
            payload: $payload,
        );
    }

    private function bookFromReference(string $reference): ?string
    {
        preg_match('/^(.+?)\s+\d/', $reference, $matches);

        return isset($matches[1]) ? trim($matches[1]) : null;
    }

    private function chapterFromReference(string $reference): ?int
    {
        preg_match('/\s(\d+):/', $reference, $matches);

        return isset($matches[1]) ? (int) $matches[1] : null;
    }

    private function verseFromReference(string $reference): ?string
    {
        preg_match('/\d+:(.+)$/', $reference, $matches);

        return isset($matches[1]) ? trim($matches[1]) : null;
    }
}
