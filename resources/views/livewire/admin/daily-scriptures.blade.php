<div class="space-y-6 sm:space-y-8">
    <section class="app-panel overflow-hidden border-blue-200 p-0">
        <div class="color-strip rounded-none"><span class="bg-blue-500"></span><span class="bg-cyan-500"></span><span class="bg-emerald-500"></span><span class="bg-amber-400"></span></div>
        <div class="p-5 sm:p-6">
            <p class="app-eyebrow border-blue-200 bg-blue-50 text-blue-900"><x-ui.icon name="book-open" class="h-4 w-4" /> Bible API</p>
            <h1 class="mt-3 app-section-title">Daily scripture integration</h1>
            <p class="mt-2 max-w-2xl text-sm leading-6 text-slate-600">Sync scripture from open verse providers while keeping devotional reflections original inside MannaRise.</p>
        </div>
    </section>

    @if ($statusMessage)
        <div class="rounded-xl border border-blue-200 bg-blue-50 px-4 py-3 text-sm font-bold text-blue-900">{!! $statusMessage !!}</div>
    @endif

    <section class="grid gap-6 lg:grid-cols-[minmax(0,1fr)_minmax(20rem,0.8fr)]">
        <form wire:submit="saveSettings" class="app-panel border-blue-200">
            <h2 class="text-xl font-black tracking-normal text-slate-950">Provider settings</h2>
            <div class="mt-5 grid gap-4 md:grid-cols-2">
                <label class="block">
                    <span class="text-sm font-bold text-slate-700">Default provider</span>
                    <select wire:model="provider" class="field-input mt-1 border-blue-300">
                        <option value="bible_api_com">bible-api.com</option>
                        <option value="our_manna">Our Manna</option>
                        <option value="api_bible">API.Bible</option>
                    </select>
                    @error('provider') <p class="mt-1 text-sm text-red-700">{{ $message }}</p> @enderror
                </label>
                <label class="block">
                    <span class="text-sm font-bold text-slate-700">Default translation</span>
                    <input type="text" wire:model="default_translation" class="field-input mt-1 border-blue-300">
                    @error('default_translation') <p class="mt-1 text-sm text-red-700">{{ $message }}</p> @enderror
                </label>
            </div>
            <div class="mt-4 grid gap-3 md:grid-cols-2">
                <label class="flex min-h-14 items-center gap-3 rounded-xl border border-blue-100 bg-blue-50 px-3 py-3 text-sm font-bold text-slate-700">
                    <input type="checkbox" wire:model="our_manna_enabled" class="rounded border-blue-300 text-blue-700 focus:ring-blue-600">
                    <span>Allow Our Manna fallback</span>
                </label>
                <label class="flex min-h-14 items-center gap-3 rounded-xl border border-blue-100 bg-blue-50 px-3 py-3 text-sm font-bold text-slate-700">
                    <input type="checkbox" wire:model="api_bible_enabled" class="rounded border-blue-300 text-blue-700 focus:ring-blue-600" @disabled(! $apiBibleConfigured)>
                    <span>Allow API.Bible fallback</span>
                </label>
            </div>
            @unless ($apiBibleConfigured)
                <p class="mt-3 text-sm leading-6 text-slate-600">API.Bible remains disabled until API_BIBLE_KEY and API_BIBLE_ID are configured.</p>
            @endunless
            <div class="mt-5 flex flex-wrap gap-2">
                <button type="submit" class="btn-primary bg-blue-700 hover:bg-blue-800"><x-ui.icon name="settings" class="h-4 w-4" /> Save settings</button>
                <button type="button" wire:click="refreshToday" class="btn-secondary border-blue-200"><x-ui.icon name="refresh-cw" class="h-4 w-4" /> Refresh today</button>
            </div>
        </form>

        <article class="app-panel border-emerald-200 bg-emerald-50">
            <p class="app-eyebrow border-emerald-200 bg-white text-emerald-900"><x-ui.icon name="star" class="h-4 w-4" /> Today</p>
            @if ($todayScripture)
                <blockquote class="mt-4 font-serif text-xl font-semibold leading-8 text-slate-950">"{{ $todayScripture->text }}"</blockquote>
                <p class="mt-4 text-sm font-black text-emerald-900">{{ $todayScripture->reference }} {{ strtoupper((string) $todayScripture->translation) }}</p>
                <div class="mt-4 grid gap-2 text-sm text-slate-700">
                    <p><span class="font-bold">Provider:</span> {{ $todayScripture->provider }}</p>
                    <p><span class="font-bold">Fetched:</span> {{ $todayScripture->fetched_at?->format('M j, Y g:i A') ?? 'Not recorded' }}</p>
                </div>
            @else
                <p class="mt-4 text-sm leading-6 text-slate-600">No daily scripture has been synced for today.</p>
            @endif
        </article>
    </section>

    <section class="overflow-x-auto rounded-xl border border-slate-200 bg-white shadow-sm">
        <table class="min-w-full divide-y divide-slate-100 text-sm">
            <thead class="bg-slate-50 text-left text-slate-700"><tr><th class="px-4 py-3 font-black">Date</th><th class="px-4 py-3 font-black">Reference</th><th class="px-4 py-3 font-black">Provider</th><th class="px-4 py-3 font-black">Fetched</th></tr></thead>
            <tbody class="divide-y divide-slate-100">
                @forelse ($recentScriptures as $scripture)
                    <tr>
                        <td class="px-4 py-3 font-bold">{{ $scripture->verse_date->format('M j, Y') }}</td>
                        <td class="px-4 py-3"><p class="font-black text-slate-950">{{ $scripture->reference }}</p><p class="max-w-xl truncate text-slate-500">{{ $scripture->text }}</p></td>
                        <td class="px-4 py-3 font-bold">{{ $scripture->provider }} / {{ strtoupper((string) $scripture->translation) }}</td>
                        <td class="px-4 py-3 text-slate-600">{{ $scripture->fetched_at?->diffForHumans() ?? 'Not recorded' }}</td>
                    </tr>
                @empty
                    <tr><td colspan="4" class="px-4 py-6 text-slate-600">Synced scriptures will appear here.</td></tr>
                @endforelse
            </tbody>
        </table>
    </section>
</div>
