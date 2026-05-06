<?php

namespace App\Services\Bible;

interface BibleProviderInterface
{
    public function name(): string;

    public function isConfigured(): bool;

    public function getDailyVerse(?string $translation = null): ?BibleVerseData;

    public function getRandomVerse(?string $translation = null): ?BibleVerseData;

    public function getPassage(string $reference, ?string $translation = null): ?BibleVerseData;
}
