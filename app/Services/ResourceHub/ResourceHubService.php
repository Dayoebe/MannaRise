<?php

namespace App\Services\ResourceHub;

use App\Models\DailyDevotion;
use App\Models\ResourceCategory;
use App\Models\ResourceItem;
use App\Services\ResourceHub\Contracts\ContentProviderInterface;
use App\Services\ResourceHub\Providers\ApiBibleProvider;
use App\Services\ResourceHub\Providers\BibleApiProvider;
use App\Services\ResourceHub\Providers\GutendexProvider;
use App\Services\ResourceHub\Providers\InternetArchiveProvider;
use App\Services\ResourceHub\Providers\LibriVoxProvider;
use App\Services\ResourceHub\Providers\OpenLibraryProvider;
use App\Services\ResourceHub\Providers\YouTubeProvider;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Throwable;

class ResourceHubService
{
    /**
     * @return array<string, ContentProviderInterface>
     */
    public function providers(): array
    {
        return [
            'bible_api' => app(BibleApiProvider::class),
            'api_bible' => app(ApiBibleProvider::class),
            'gutendex' => app(GutendexProvider::class),
            'internet_archive' => app(InternetArchiveProvider::class),
            'open_library' => app(OpenLibraryProvider::class),
            'librivox' => app(LibriVoxProvider::class),
            'youtube' => app(YouTubeProvider::class),
        ];
    }

    /**
     * @return array<int, array<string, mixed>>
     */
    public function searchExternal(string $provider, string $query, array $options = []): array
    {
        $instance = $this->providers()[$provider] ?? null;

        if (! $instance || ! config("resourcehub.providers.{$provider}.enabled", true)) {
            return [];
        }

        try {
            return $instance->search($query, $options);
        } catch (Throwable $exception) {
            Log::warning('Resource Hub provider failed.', [
                'provider' => $provider,
                'query' => $query,
                'message' => $exception->getMessage(),
            ]);

            return [];
        }
    }

    /**
     * @return Collection<int, ResourceItem>
     */
    public function import(string $provider, string $query, array $options = []): Collection
    {
        return collect($this->searchExternal($provider, $query, $options))
            ->map(fn (array $item) => $this->storeNormalizedItem($item))
            ->filter();
    }

    public function storeNormalizedItem(array $item): ?ResourceItem
    {
        $sourceName = $item['source_name'] ?? null;
        $externalId = $item['external_id'] ?? null;

        if (! filled($sourceName) || ! filled($externalId)) {
            return null;
        }

        $categoryId = $this->categoryForType($item['type'] ?? null)?->id;
        $title = trim((string) ($item['title'] ?? 'Resource'));
        $existing = ResourceItem::where('source_name', $sourceName)
            ->where('external_id', (string) $externalId)
            ->first();

        return ResourceItem::updateOrCreate(
            [
                'source_name' => $sourceName,
                'external_id' => (string) $externalId,
            ],
            [
                'resource_category_id' => $categoryId,
                'title' => $title,
                'slug' => $existing?->slug ?: ResourceItem::uniqueSlug($title),
                'excerpt' => $item['excerpt'] ?? null,
                'description' => $item['description'] ?? null,
                'content' => $item['content'] ?? null,
                'type' => $item['type'] ?? 'article',
                'source_url' => $item['source_url'] ?? null,
                'author' => $item['author'] ?? null,
                'thumbnail_url' => $item['thumbnail_url'] ?? null,
                'media_url' => $item['media_url'] ?? null,
                'embed_url' => $item['embed_url'] ?? null,
                'language' => $item['language'] ?? 'en',
                'license' => $item['license'] ?? null,
                'tags' => $item['tags'] ?? [],
                'metadata' => $item['metadata'] ?? [],
                'is_published' => $item['is_published'] ?? true,
                'published_at' => $item['published_at'] ?? now(),
            ],
        );
    }

    public function categoryForType(?string $type): ?ResourceCategory
    {
        return $type
            ? ResourceCategory::active()->where('type', $type)->first()
            : null;
    }

    public function todayDevotion(): ?DailyDevotion
    {
        return DailyDevotion::published()
            ->whereDate('devotion_date', today())
            ->first()
            ?: DailyDevotion::published()->whereDate('devotion_date', '<=', today())->latest('devotion_date')->first();
    }

    public function prepareTodayDevotion(): DailyDevotion
    {
        $today = today();

        return DailyDevotion::firstOrCreate(
            ['devotion_date' => $today->toDateString()],
            [
                'title' => 'Grace for Today',
                'slug' => DailyDevotion::uniqueSlug('Grace for Today '.$today->format('Y-m-d')),
                'bible_reference' => 'Lamentations 3:22-23',
                'bible_text' => 'It is of the Lord\'s mercies that we are not consumed, because his compassions fail not. They are new every morning: great is thy faithfulness.',
                'memory_verse' => 'Great is thy faithfulness.',
                'devotion_text' => 'Begin today by receiving mercy before measuring performance. God meets the day with compassion, and His faithfulness gives strength for the next obedient step.',
                'prayer' => 'Father, let Your mercy steady my heart today. Teach me to walk in faith, patience, and love.',
                'reflection_questions' => ['Where do I need to receive mercy today?', 'What faithful step can I take before the day ends?'],
                'action_point' => 'Pause for two minutes and name one mercy you can thank God for today.',
                'author' => 'MannaRise',
                'is_published' => true,
            ],
        );
    }

    public static function embedFromYouTube(?string $url): ?string
    {
        if (! $url) {
            return null;
        }

        if (preg_match('#(?:youtube\.com/watch\?v=|youtu\.be/|youtube\.com/embed/)([A-Za-z0-9_-]{6,})#', $url, $matches)) {
            return 'https://www.youtube.com/embed/'.$matches[1];
        }

        return Str::startsWith($url, 'https://www.youtube.com/embed/') ? $url : null;
    }
}
