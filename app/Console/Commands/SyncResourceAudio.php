<?php

namespace App\Console\Commands;

use App\Services\ResourceHub\ResourceHubService;
use Illuminate\Console\Command;
use Throwable;

class SyncResourceAudio extends Command
{
    protected $signature = 'resources:sync-audio {query?}';

    protected $description = 'Sync public-domain audio resources.';

    public function handle(ResourceHubService $service): int
    {
        $keywords = $this->argument('query') ? [$this->argument('query')] : config('resourcehub.audio_keywords', []);
        $total = 0;

        foreach ($keywords as $keyword) {
            try {
                $total += $service->import('librivox', $keyword, ['limit' => 10])->count();
                $total += $service->import('internet_archive', $keyword, ['limit' => 10, 'mediatype' => 'audio'])->count();
            } catch (Throwable $exception) {
                $this->warn("Audio sync failed for {$keyword}: {$exception->getMessage()}");
            }
        }

        $this->info("Audio sync complete. {$total} resources refreshed.");

        return self::SUCCESS;
    }
}
