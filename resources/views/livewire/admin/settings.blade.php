<div class="space-y-6 sm:space-y-8">
    <section class="app-panel overflow-hidden border-slate-200 p-0 sm:p-0">
        <div class="color-strip rounded-none">
            <span class="bg-slate-500"></span>
            <span class="bg-indigo-500"></span>
            <span class="bg-blue-500"></span>
            <span class="bg-emerald-500"></span>
            <span class="bg-amber-400"></span>
        </div>
        <div class="p-5 sm:p-6">
            <p class="app-eyebrow border-slate-200 bg-slate-50 text-slate-900"><x-ui.icon name="settings" class="h-4 w-4" /> Settings</p>
            <h1 class="mt-3 app-section-title">Platform settings</h1>
            <p class="mt-2 max-w-2xl text-sm leading-6 text-slate-600">Control public copy, daily rhythm modules, moderation defaults, and reminder defaults from one admin screen.</p>
        </div>
    </section>

    <form wire:submit="save" class="app-panel border-slate-200">
        <div class="grid gap-6 lg:grid-cols-[minmax(0,1fr)_minmax(18rem,0.8fr)]">
            <div class="space-y-5">
                <div class="grid gap-4 md:grid-cols-2">
                    <div>
                        <label class="block text-sm font-bold text-slate-700">Site name</label>
                        <input type="text" wire:model="site_name" class="field-input mt-1">
                        @error('site_name') <p class="mt-1 text-sm text-red-700">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-bold text-slate-700">Support email</label>
                        <input type="email" wire:model="support_email" class="field-input mt-1">
                        @error('support_email') <p class="mt-1 text-sm text-red-700">{{ $message }}</p> @enderror
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-bold text-slate-700">Site tagline</label>
                    <input type="text" wire:model="site_tagline" class="field-input mt-1">
                    @error('site_tagline') <p class="mt-1 text-sm text-red-700">{{ $message }}</p> @enderror
                </div>

                <div class="grid gap-4 md:grid-cols-2">
                    <div>
                        <label class="block text-sm font-bold text-slate-700">Default reading time</label>
                        <input type="number" min="1" max="120" wire:model="default_reading_time" class="field-input mt-1">
                        @error('default_reading_time') <p class="mt-1 text-sm text-red-700">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-bold text-slate-700">Default timezone</label>
                        <input type="text" wire:model="default_timezone" class="field-input mt-1">
                        @error('default_timezone') <p class="mt-1 text-sm text-red-700">{{ $message }}</p> @enderror
                    </div>
                </div>

                <div class="grid gap-3 md:grid-cols-2">
                    @foreach ([
                        ['daily_verse_enabled', 'Verse of the day'],
                        ['daily_affirmations_enabled', 'Daily affirmations'],
                        ['daily_bible_challenge_enabled', 'Bible-in-a-year challenge'],
                        ['prayer_public_default', 'Prayer requests public by default'],
                        ['testimony_requires_approval', 'Testimonies require approval'],
                    ] as [$model, $label])
                        <label class="flex min-h-14 items-center gap-3 rounded-xl border border-slate-200 bg-slate-50 px-3 py-3 text-sm font-bold text-slate-700">
                            <input type="checkbox" wire:model="{{ $model }}" class="rounded border-slate-300 text-emerald-700 focus:ring-emerald-600">
                            <span>{{ $label }}</span>
                        </label>
                    @endforeach
                </div>

                <button type="submit" class="btn-primary"><x-ui.icon name="settings" class="h-4 w-4" /> Save settings</button>
            </div>

            <aside class="rounded-xl border border-indigo-100 bg-indigo-50 p-4">
                <h2 class="flex items-center gap-2 font-black tracking-normal text-slate-950"><x-ui.icon name="bar-chart" class="h-4 w-4 text-indigo-800" /> System snapshot</h2>
                <div class="mt-4 space-y-3">
                    @foreach ($systemInfo as $label => $value)
                        <div class="rounded-xl border border-indigo-100 bg-white p-3">
                            <p class="text-xs font-black uppercase tracking-normal text-indigo-800">{{ $label }}</p>
                            <p class="mt-1 break-words text-sm font-bold text-slate-700">{{ $value }}</p>
                        </div>
                    @endforeach
                </div>
            </aside>
        </div>
    </form>

    <section>
        <div class="mb-4">
            <p class="app-eyebrow border-emerald-200 bg-emerald-50 text-emerald-900"><x-ui.icon name="check-circle" class="h-4 w-4" /> Active settings</p>
            <h2 class="mt-3 app-section-title">Configuration overview</h2>
        </div>

        <div class="grid gap-4 lg:grid-cols-2">
            @foreach ($settingsRows as $group => $rows)
                <article class="app-panel border-slate-200">
                    <h3 class="font-black tracking-normal text-slate-950">{{ $group }}</h3>
                    <div class="mt-4 space-y-3">
                        @foreach ($rows as $row)
                            <div class="rounded-xl border border-slate-100 bg-slate-50 p-3">
                                <div class="flex items-start justify-between gap-3">
                                    <div>
                                        <p class="font-bold text-slate-900">{{ $row['label'] }}</p>
                                        <p class="mt-1 text-sm leading-6 text-slate-600">{{ $row['description'] }}</p>
                                    </div>
                                    <span class="shrink-0 rounded-full bg-white px-3 py-1 text-xs font-black text-slate-700 shadow-sm">
                                        @if (is_bool($row['value']))
                                            {{ $row['value'] ? 'On' : 'Off' }}
                                        @else
                                            {{ $row['value'] !== '' ? $row['value'] : 'Not set' }}
                                        @endif
                                    </span>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </article>
            @endforeach
        </div>
    </section>
</div>
