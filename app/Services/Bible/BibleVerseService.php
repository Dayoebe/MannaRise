<?php

namespace App\Services\Bible;

use App\Models\DailyScripture;
use App\Models\PlatformSetting;
use Carbon\CarbonInterface;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Throwable;

class BibleVerseService
{
    /**
     * @param  array<string, BibleProviderInterface>|null  $providers
     */
    public function __construct(private ?array $providers = null) {}

    public function getDailyVerse(?string $provider = null, ?CarbonInterface $date = null, bool $force = false): ?BibleVerseData
    {
        $date ??= today();
        $provider ??= $this->configuredProvider();
        $cacheKey = 'bible.daily_verse.'.$date->toDateString().'.'.$provider;

        if ($force) {
            Cache::forget($cacheKey);
        }

        return Cache::remember($cacheKey, $this->cacheTtl(), function () use ($provider) {
            return $this->fromProviders($this->providerOrder($provider), fn (BibleProviderInterface $provider) => $provider->getDailyVerse());
        });
    }

    public function getRandomVerse(?string $provider = null): ?BibleVerseData
    {
        return $this->fromProviders($this->providerOrder($provider), fn (BibleProviderInterface $provider) => $provider->getRandomVerse());
    }

    public function getPassage(string $reference, ?string $translation = null, ?string $provider = null): ?BibleVerseData
    {
        $translation ??= (string) (PlatformSetting::value('bible.default_translation') ?: config('bible.default_translation'));
        $cacheKey = 'bible.passage.'.md5($reference.'|'.$translation.'|'.($provider ?? 'auto'));

        return Cache::remember($cacheKey, $this->cacheTtl(), function () use ($reference, $translation, $provider) {
            return $this->fromProviders($this->providerOrder($provider), function (BibleProviderInterface $provider) use ($reference, $translation) {
                $verse = $provider->getPassage($reference, $translation);
                $fallbackTranslation = (string) config('bible.fallback_translation', 'kjv');

                if (! $verse && $fallbackTranslation !== $translation) {
                    return $provider->getPassage($reference, $fallbackTranslation);
                }

                return $verse;
            });
        });
    }

    public function todayFromStorageOrFetch(bool $allowFetch = false): ?DailyScripture
    {
        $scripture = DailyScripture::query()->active()->forToday()->first();

        if ($scripture || ! $allowFetch) {
            return $scripture;
        }

        $verse = $this->getDailyVerse();

        if (! $verse) {
            return null;
        }

        return DailyScripture::updateOrCreate(
            ['verse_date' => today()->toDateString()],
            [
                ...$verse->toArray(),
                'is_active' => true,
                'fetched_at' => now(),
            ],
        );
    }

    /**
     * @param  array<int, string>  $providerNames
     * @param  callable(BibleProviderInterface): ?BibleVerseData  $callback
     */
    private function fromProviders(array $providerNames, callable $callback): ?BibleVerseData
    {
        foreach ($providerNames as $providerName) {
            $provider = $this->providers()[$providerName] ?? null;

            if (! $provider || ! $provider->isConfigured()) {
                continue;
            }

            try {
                $verse = $callback($provider);

                if ($verse) {
                    return $verse;
                }
            } catch (Throwable $exception) {
                Log::warning('Bible provider request failed.', [
                    'provider' => $providerName,
                    'message' => $exception->getMessage(),
                ]);
            }
        }

        return null;
    }

    /**
     * @return array<int, string>
     */
    private function providerOrder(?string $preferred = null): array
    {
        $providers = array_values(array_unique(array_filter([
            $preferred,
            $this->configuredProvider(),
            'bible_api_com',
            'our_manna',
            'api_bible',
            ...array_keys($this->providers()),
        ])));

        return array_values(array_filter($providers, fn (string $provider): bool => array_key_exists($provider, $this->providers())));
    }

    private function configuredProvider(): string
    {
        return (string) (PlatformSetting::value('bible.provider') ?: config('bible.default_provider', 'bible_api_com'));
    }

    private function cacheTtl(): int
    {
        return max(60, (int) config('bible.cache_ttl', 86400));
    }

    /**
     * @return array<string, BibleProviderInterface>
     */
    private function providers(): array
    {
        return $this->providers ??= [
            'bible_api_com' => app(BibleApiComProvider::class),
            'our_manna' => app(OurMannaProvider::class),
            'api_bible' => app(ApiBibleProvider::class),
        ];
    }
}
