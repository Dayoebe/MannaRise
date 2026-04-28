<?php

namespace App\Livewire\Admin;

use App\Models\PlatformSetting;
use Livewire\Component;

class Settings extends Component
{
    public string $site_name = '';
    public string $site_tagline = '';
    public string $support_email = '';
    public int $default_reading_time = 5;
    public bool $daily_verse_enabled = true;
    public bool $daily_affirmations_enabled = true;
    public bool $daily_bible_challenge_enabled = true;
    public bool $prayer_public_default = true;
    public bool $testimony_requires_approval = true;
    public string $default_timezone = 'Africa/Lagos';

    public function mount(): void
    {
        $this->site_name = PlatformSetting::value('site.name');
        $this->site_tagline = PlatformSetting::value('site.tagline');
        $this->support_email = PlatformSetting::value('site.support_email');
        $this->default_reading_time = PlatformSetting::value('content.default_reading_time');
        $this->daily_verse_enabled = PlatformSetting::value('daily.verse_enabled');
        $this->daily_affirmations_enabled = PlatformSetting::value('daily.affirmations_enabled');
        $this->daily_bible_challenge_enabled = PlatformSetting::value('daily.bible_challenge_enabled');
        $this->prayer_public_default = PlatformSetting::value('moderation.prayer_public_default');
        $this->testimony_requires_approval = PlatformSetting::value('moderation.testimony_requires_approval');
        $this->default_timezone = PlatformSetting::value('notifications.default_timezone');
    }

    public function save(): void
    {
        $validated = $this->validate([
            'site_name' => ['required', 'string', 'max:80'],
            'site_tagline' => ['nullable', 'string', 'max:180'],
            'support_email' => ['nullable', 'email', 'max:255'],
            'default_reading_time' => ['required', 'integer', 'min:1', 'max:120'],
            'daily_verse_enabled' => ['boolean'],
            'daily_affirmations_enabled' => ['boolean'],
            'daily_bible_challenge_enabled' => ['boolean'],
            'prayer_public_default' => ['boolean'],
            'testimony_requires_approval' => ['boolean'],
            'default_timezone' => ['required', 'timezone'],
        ]);

        PlatformSetting::write('site.name', $validated['site_name']);
        PlatformSetting::write('site.tagline', $validated['site_tagline'] ?? '');
        PlatformSetting::write('site.support_email', $validated['support_email'] ?? '');
        PlatformSetting::write('content.default_reading_time', $validated['default_reading_time']);
        PlatformSetting::write('daily.verse_enabled', $validated['daily_verse_enabled']);
        PlatformSetting::write('daily.affirmations_enabled', $validated['daily_affirmations_enabled']);
        PlatformSetting::write('daily.bible_challenge_enabled', $validated['daily_bible_challenge_enabled']);
        PlatformSetting::write('moderation.prayer_public_default', $validated['prayer_public_default']);
        PlatformSetting::write('moderation.testimony_requires_approval', $validated['testimony_requires_approval']);
        PlatformSetting::write('notifications.default_timezone', $validated['default_timezone']);

        session()->flash('status', 'Platform settings saved.');
    }

    public function render()
    {
        return view('livewire.admin.settings', [
            'settingsRows' => PlatformSetting::allWithDefaults()->groupBy('group'),
            'systemInfo' => [
                'App environment' => app()->environment(),
                'Laravel timezone' => config('app.timezone'),
                'Queue connection' => config('queue.default'),
                'Mail transport' => config('mail.default'),
                'Cache store' => config('cache.default'),
            ],
        ]);
    }
}
