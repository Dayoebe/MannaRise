<div class="space-y-6 sm:space-y-8">
    <section class="app-panel border-sky-200 bg-white">
        <div class="flex flex-col gap-4 md:flex-row md:items-end md:justify-between">
            <div>
                <p class="app-eyebrow border-sky-200 bg-sky-50 text-sky-900"><x-ui.icon name="video" class="h-4 w-4" /> Videos</p>
                <h1 class="mt-3 app-section-title">Video teaching and devotion</h1>
                <p class="mt-2 text-sm leading-6 text-slate-600">Manual YouTube embeds and optional YouTube API resources.</p>
            </div>
            @if (auth()->user()?->hasAdminAccess())
                <button type="button" wire:click="importExternal" class="btn-primary bg-sky-700 hover:bg-sky-800"><x-ui.icon name="download" class="h-4 w-4" /> Refresh videos</button>
            @endif
        </div>
        <label for="resource-videos-search" class="sr-only">Search videos</label>
        <input id="resource-videos-search" type="search" wire:model.live.debounce.350ms="search" placeholder="Search videos" class="field-input mt-5 border-sky-300 focus:border-sky-700 focus:ring-sky-100">
    </section>

    <div class="grid gap-4 lg:grid-cols-3">
        @forelse ($videos as $video)
            <a href="{{ route('resources.show', $video->slug) }}" class="app-panel app-panel-hover border-slate-200 bg-white">
                <div class="aspect-video overflow-hidden rounded-xl bg-slate-950">
                    @if ($video->thumbnail_url)
                        <img src="{{ $video->thumbnail_url }}" alt="{{ $video->title }} video thumbnail" loading="lazy" width="640" height="360" class="h-full w-full object-cover">
                    @endif
                </div>
                <p class="mt-4 text-xs font-black uppercase text-sky-800">{{ $video->source_name ?: 'Video' }}</p>
                <h2 class="mt-2 text-xl font-black tracking-normal text-slate-950">{{ $video->title }}</h2>
                <p class="mt-2 text-sm leading-6 text-slate-600">{{ $video->excerpt }}</p>
            </a>
        @empty
            <div class="app-panel border-dashed border-slate-300 text-sm text-slate-600 lg:col-span-3">No videos yet. Add manual embeds from admin or configure YouTube API.</div>
        @endforelse
    </div>
    <div>{{ $videos->links() }}</div>
</div>
