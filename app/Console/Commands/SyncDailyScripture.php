<?php

namespace App\Console\Commands;

use App\Models\DailyScripture;
use App\Services\Bible\BibleVerseService;
use Carbon\CarbonImmutable;
use Illuminate\Console\Command;
use Throwable;

class SyncDailyScripture extends Command
{
    protected $signature = 'mannarise:sync-daily-scripture {--provider=} {--date=} {--force}';

    protected $description = 'Fetch and store the configured daily scripture verse.';

    public function handle(BibleVerseService $service): int
    {
        $date = CarbonImmutable::parse($this->option('date') ?: today())->startOfDay();
        $provider = $this->option('provider') ?: null;
        $force = (bool) $this->option('force');

        if (! $force && DailyScripture::whereDate('verse_date', $date)->exists()) {
            $this->components->info('Daily scripture already exists for '.$date->toDateString().'. Use --force to refresh it.');

            return self::SUCCESS;
        }

        try {
            $verse = $service->getDailyVerse($provider, $date, $force);
        } catch (Throwable $exception) {
            $this->components->error('Daily scripture sync failed: '.$exception->getMessage());

            return self::FAILURE;
        }

        if (! $verse) {
            $this->components->warn('No daily scripture could be fetched. The API may be unavailable or the provider is not configured.');

            return self::FAILURE;
        }

        $scripture = DailyScripture::updateOrCreate(
            ['verse_date' => $date->toDateString()],
            [
                ...$verse->toArray(),
                'is_active' => true,
                'fetched_at' => now(),
            ],
        );

        $this->components->info(sprintf(
            'Daily scripture saved for %s: %s (%s via %s).',
            $scripture->verse_date->toDateString(),
            $scripture->reference,
            strtoupper((string) $scripture->translation),
            $scripture->provider,
        ));

        return self::SUCCESS;
    }
}
