<?php

namespace App\Services\Bible;

final class BibleVerseData
{
    public function __construct(
        public readonly string $provider,
        public readonly string $reference,
        public readonly string $text,
        public readonly ?string $translation = null,
        public readonly ?string $book = null,
        public readonly ?int $chapter = null,
        public readonly ?string $verse = null,
        public readonly ?array $payload = null,
    ) {}

    public function toArray(): array
    {
        return [
            'provider' => $this->provider,
            'reference' => $this->reference,
            'book' => $this->book,
            'chapter' => $this->chapter,
            'verse' => $this->verse,
            'translation' => $this->translation,
            'text' => $this->text,
            'response_payload' => $this->payload,
        ];
    }
}
