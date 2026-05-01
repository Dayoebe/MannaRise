<?php

namespace App\Console\Commands;

use App\Services\ResourceHub\ResourceHubService;
use Illuminate\Console\Command;
use Throwable;

class SyncResourceVideos extends Command
{
    protected $signature = 'resources:sync-videos {query?}';

    protected $description = 'Sync YouTube video resources when the API key is configured.';

    public function handle(ResourceHubService $service): int
    {
        if (! config('resourcehub.providers.youtube.key')) {
            $this->warn('YOUTUBE_API_KEY is not configured. Manual video embeds still work.');

            return self::SUCCESS;
        }

        $keywords = $this->argument('query') ? [$this->argument('query')] : config('resourcehub.youtube_keywords', []);
        $total = 0;

        foreach ($keywords as $keyword) {
            try {
                $total += $service->import('youtube', $keyword, ['limit' => 10])->count();
            } catch (Throwable $exception) {
                $this->warn("Video sync failed for {$keyword}: {$exception->getMessage()}");
            }
        }

        $this->info("Video sync complete. {$total} resources refreshed.");

        return self::SUCCESS;
    }
}
