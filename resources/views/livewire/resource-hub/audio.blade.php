<div class="space-y-6 sm:space-y-8">
    <section class="app-panel border-amber-200 bg-white">
        <div class="flex flex-col gap-4 md:flex-row md:items-end md:justify-between">
            <div>
                <p class="app-eyebrow border-amber-200 bg-amber-50 text-amber-900"><x-ui.icon name="headphones" class="h-4 w-4" /> Audio</p>
                <h1 class="mt-3 app-section-title">Sermons, teaching, and audiobooks</h1>
                <p class="mt-2 text-sm leading-6 text-slate-600">Listen to local teaching, devotionals, sermons, and public-domain audiobooks.</p>
            </div>
            @if (auth()->user()?->hasAdminAccess())
                <button type="button" wire:click="importExternal" class="btn-primary bg-amber-600 text-slate-950 hover:bg-amber-500"><x-ui.icon name="download" class="h-4 w-4" /> Refresh audio</button>
            @endif
        </div>
        <input type="search" wire:model.live.debounce.350ms="search" placeholder="Search audio" class="field-input mt-5 border-amber-300 focus:border-amber-700 focus:ring-amber-100">
    </section>

    <div class="space-y-4">
        @forelse ($audioItems as $audio)
            <article class="app-panel border-slate-200 bg-white">
                <div class="grid gap-4 lg:grid-cols-[minmax(0,1fr)_18rem] lg:items-center">
                    <div>
                        <p class="text-xs font-black uppercase text-amber-800">{{ $audio->source_name ?: 'Audio' }}</p>
                        <h2 class="mt-2 text-xl font-black tracking-normal text-slate-950">{{ $audio->title }}</h2>
                        <p class="mt-2 text-sm font-bold text-slate-500">{{ $audio->author }}</p>
                        <p class="mt-3 text-sm leading-6 text-slate-600">{{ $audio->excerpt ?: \Illuminate\Support\Str::limit(strip_tags($audio->description), 140) }}</p>
                    </div>
                    <div>
                        @if ($audio->media_url)
                            <audio controls src="{{ $audio->media_url }}" class="w-full"></audio>
                        @endif
                        <a href="{{ route('resources.show', $audio->slug) }}" class="btn-secondary mt-3 w-full border-amber-200">Open details</a>
                    </div>
                </div>
            </article>
        @empty
            <div class="app-panel border-dashed border-slate-300 text-sm text-slate-600">No audio resources yet. An admin can sync public-domain audio from supported providers.</div>
        @endforelse
    </div>
    <div>{{ $audioItems->links() }}</div>
</div>
