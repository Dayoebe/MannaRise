<div class="space-y-6 sm:space-y-8">
    <article class="app-panel overflow-hidden border-emerald-200 bg-white p-0">
        <div class="color-strip rounded-none">
            <span class="bg-emerald-500"></span>
            <span class="bg-sky-500"></span>
            <span class="bg-amber-400"></span>
            <span class="bg-fuchsia-500"></span>
        </div>
        <div class="p-5 sm:p-8">
            <div class="flex flex-col gap-4 md:flex-row md:items-start md:justify-between">
                <div>
                    <p class="app-eyebrow border-emerald-200 bg-emerald-50 text-emerald-900"><x-ui.icon :name="$resource->category?->icon ?: 'library'" class="h-4 w-4" /> {{ $resource->type }}</p>
                    <h1 class="mt-4 max-w-4xl font-display text-4xl font-black leading-tight tracking-normal text-slate-950">{{ $resource->title }}</h1>
                    <p class="mt-3 text-sm font-bold text-slate-500">{{ $resource->author ?: $resource->source_name }} @if ($resource->license) · {{ $resource->license }} @endif</p>
                </div>
                @auth
                    <button type="button" wire:click="toggleBookmark" class="btn-secondary border-emerald-200 px-3">
                        <x-ui.icon name="bookmark" class="h-4 w-4" /> {{ $isBookmarked ? 'Bookmarked' : 'Bookmark' }}
                    </button>
                @else
                    <a href="{{ route('login') }}" class="btn-secondary border-emerald-200 px-3"><x-ui.icon name="log-in" class="h-4 w-4" /> Log in to bookmark</a>
                @endauth
            </div>

            @if ($resource->thumbnail_url && ! in_array($resource->type, ['video'], true))
                <img src="{{ $resource->thumbnail_url }}" alt="" class="mt-6 max-h-96 w-full rounded-2xl object-cover">
            @endif

            @if ($resource->type === 'video' && $resource->embed_url)
                <div class="mt-6 aspect-video overflow-hidden rounded-2xl border border-slate-200 bg-slate-950">
                    <iframe class="h-full w-full" src="{{ $resource->embed_url }}" title="{{ $resource->title }}" allowfullscreen loading="lazy"></iframe>
                </div>
            @elseif (in_array($resource->type, ['audio', 'sermon'], true) && $resource->media_url)
                <div class="mt-6 rounded-2xl border border-amber-200 bg-amber-50 p-4">
                    <audio controls class="w-full" src="{{ $resource->media_url }}"></audio>
                </div>
            @endif

            @if ($resource->excerpt)
                <p class="mt-6 text-lg font-semibold leading-8 text-slate-700">{{ $resource->excerpt }}</p>
            @endif

            @if ($resource->content || $resource->description)
                <div class="reading-copy mt-6 max-w-4xl text-slate-800">
                    {!! nl2br(e($resource->content ?: $resource->description)) !!}
                </div>
            @endif

            <div class="mt-8 grid gap-4 rounded-2xl border border-slate-200 bg-slate-50 p-4 text-sm text-slate-700 md:grid-cols-3">
                <div><span class="font-black text-slate-950">Source:</span> {{ $resource->source_name ?: 'MannaRise' }}</div>
                <div><span class="font-black text-slate-950">Language:</span> {{ strtoupper($resource->language) }}</div>
                <div><span class="font-black text-slate-950">License:</span> {{ $resource->license ?: 'See source' }}</div>
                @if ($resource->source_url)
                    <a href="{{ $resource->source_url }}" target="_blank" rel="noopener noreferrer" class="font-black text-emerald-800 hover:underline md:col-span-3">Open original source</a>
                @endif
            </div>

            @auth
                <form wire:submit="updateProgress" class="mt-6 rounded-2xl border border-emerald-200 bg-emerald-50 p-4">
                    <label class="block text-sm font-black text-emerald-950">Your progress: {{ $progressValue }}%</label>
                    <input type="range" min="0" max="100" wire:model="progressValue" class="mt-3 w-full accent-emerald-700">
                    <button type="submit" class="btn-primary mt-3 bg-emerald-700 hover:bg-emerald-800">Save progress</button>
                </form>
            @endauth
        </div>
    </article>

    @if ($related->isNotEmpty())
        <section>
            <h2 class="mb-4 text-2xl font-black tracking-normal text-slate-950">Related resources</h2>
            <div class="grid gap-4 md:grid-cols-2 lg:grid-cols-4">
                @foreach ($related as $item)
                    <a href="{{ route('resources.show', $item->slug) }}" class="app-panel app-panel-hover border-slate-200 bg-white">
                        <p class="text-xs font-black uppercase text-emerald-800">{{ $item->type }}</p>
                        <h3 class="mt-2 font-black tracking-normal text-slate-950">{{ $item->title }}</h3>
                    </a>
                @endforeach
            </div>
        </section>
    @endif
</div>
