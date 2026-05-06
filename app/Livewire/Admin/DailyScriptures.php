<?php

namespace App\Livewire\Admin;

use App\Models\DailyScripture;
use App\Models\PlatformSetting;
use App\Services\Bible\BibleVerseService;
use App\Support\Toast;
use Livewire\Component;

class DailyScriptures extends Component
{
    public string $provider = 'bible_api_com';

    public string $default_translation = 'web';

    public bool $our_manna_enabled = true;

    public bool $api_bible_enabled = false;

    public ?string $statusMessage = null;

    public function mount(): void
    {
        $this->provider = (string) (PlatformSetting::value('bible.provider') ?: config('bible.default_provider'));
        $this->default_translation = (string) (PlatformSetting::value('bible.default_translation') ?: config('bible.default_translation'));
        $this->our_manna_enabled = (bool) PlatformSetting::value('bible.our_manna_enabled');
        $this->api_bible_enabled = (bool) PlatformSetting::value('bible.api_bible_enabled');
    }

    public function saveSettings(): void
    {
        $validated = $this->validate([
            'provider' => ['required', 'in:bible_api_com,our_manna,api_bible'],
            'default_translation' => ['required', 'string', 'max:20'],
            'our_manna_enabled' => ['boolean'],
            'api_bible_enabled' => ['boolean'],
        ]);

        PlatformSetting::write('bible.provider', $validated['provider']);
        PlatformSetting::write('bible.default_translation', $validated['default_translation']);
        PlatformSetting::write('bible.our_manna_enabled', $validated['our_manna_enabled']);
        PlatformSetting::write('bible.api_bible_enabled', $validated['api_bible_enabled']);

        Toast::status($this, 'Daily scripture settings saved.');
    }

    public function refreshToday(BibleVerseService $service): void
    {
        $verse = $service->getDailyVerse($this->provider, today(), true);

        if (! $verse) {
            $this->statusMessage = 'The selected provider did not return a verse. Check provider settings or try again later.';

            return;
        }

        DailyScripture::updateOrCreate(
            ['verse_date' => today()->toDateString()],
            [
                ...$verse->toArray(),
                'is_active' => true,
                'fetched_at' => now(),
            ],
        );

        $this->statusMessage = "Today's scripture was refreshed.";
    }

    public function render()
    {
        return view('livewire.admin.daily-scriptures', [
            'todayScripture' => DailyScripture::query()->forToday()->first(),
            'recentScriptures' => DailyScripture::query()->latest('verse_date')->take(10)->get(),
            'apiBibleConfigured' => filled(config('bible.providers.api_bible.key')) && filled(config('bible.providers.api_bible.bible_id')),
        ]);
    }
}
