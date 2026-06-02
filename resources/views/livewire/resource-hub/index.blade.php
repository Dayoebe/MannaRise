<div class="space-y-6 sm:space-y-8">
    <section class="overflow-hidden rounded-3xl border border-emerald-200 bg-white shadow-sm">
        <div class="color-strip rounded-none">
            <span class="bg-emerald-500"></span>
            <span class="bg-lime-500"></span>
            <span class="bg-amber-400"></span>
            <span class="bg-sky-500"></span>
            <span class="bg-indigo-500"></span>
            <span class="bg-fuchsia-500"></span>
        </div>
        <div class="grid gap-6 bg-gradient-to-br from-emerald-50 via-white to-amber-50 p-5 sm:p-8 lg:grid-cols-[minmax(0,1.35fr)_minmax(20rem,0.65fr)] lg:items-end">
            <div>
                <p class="app-eyebrow border-emerald-200 bg-white text-emerald-900"><x-ui.icon name="library" class="h-4 w-4" /> Resource Hub</p>
                <h1 class="mt-4 max-w-3xl font-display text-4xl font-black leading-tight tracking-normal text-slate-950 sm:text-5xl">Spiritual and educational resources for daily growth</h1>
                <p class="mt-4 max-w-2xl text-base leading-7 text-slate-650">Explore devotion guides, scripture, books, videos, sermons, audio teaching, and public-domain learning resources in one calm library.</p>
            </div>
            @if ($todayDevotion)
                <a href="{{ route('resources.devotion.show', $todayDevotion->slug) }}" class="rounded-2xl border border-amber-200 bg-white p-5 shadow-sm transition hover:border-amber-300 hover:bg-amber-50">
                    <p class="text-xs font-black uppercase tracking-[0.18em] text-amber-700">Today</p>
                    <h2 class="mt-2 text-2xl font-black tracking-normal text-slate-950">{{ $todayDevotion->title }}</h2>
                    <p class="mt-3 text-sm leading-6 text-slate-600">{{ \Illuminate\Support\Str::limit(strip_tags($todayDevotion->devotion_text), 130) }}</p>
                    <span class="mt-4 inline-flex items-center gap-2 text-sm font-black text-emerald-900">Open devotion <x-ui.icon name="chevron-right" class="h-4 w-4" /></span>
                </a>
            @endif
        </div>
    </section>

    <section class="app-panel border-emerald-200 bg-emerald-50">
        <div class="grid gap-3 lg:grid-cols-[minmax(0,1fr)_12rem_16rem]">
            <label for="resource-search" class="sr-only">Search resources</label>
            <input id="resource-search" type="search" wire:model.live.debounce.350ms="search" placeholder="Search resources, authors, topics..." class="field-input border-emerald-300 bg-white focus:border-emerald-700 focus:ring-emerald-100">
            <label for="resource-type" class="sr-only">Resource type</label>
            <select id="resource-type" wire:model.live="type" class="field-input border-emerald-300 bg-white focus:border-emerald-700 focus:ring-emerald-100">
                <option value="">All types</option>
                <option value="devotion">Devotion</option>
                <option value="bible">Bible</option>
                <option value="book">Book</option>
                <option value="video">Video</option>
                <option value="audio">Audio</option>
                <option value="article">Article</option>
                <option value="education">Education</option>
            </select>
            <label for="resource-category" class="sr-only">Resource category</label>
            <select id="resource-category" wire:model.live="category" class="field-input border-emerald-300 bg-white focus:border-emerald-700 focus:ring-emerald-100">
                <option value="">All categories</option>
                @foreach ($categories as $resourceCategory)
                    <option value="{{ $resourceCategory->id }}">{{ $resourceCategory->name }}</option>
                @endforeach
            </select>
        </div>
    </section>

    <section class="grid gap-4 md:grid-cols-2 xl:grid-cols-3">
        @foreach ($categories as $resourceCategory)
            <a href="{{ route('resources.index', ['category' => $resourceCategory->id]) }}" class="app-panel app-panel-hover border-slate-200 bg-white">
                <div class="flex items-start gap-3">
                    <span class="inline-flex h-11 w-11 shrink-0 items-center justify-center rounded-2xl bg-emerald-50 text-emerald-800"><x-ui.icon :name="$resourceCategory->icon ?: 'library'" class="h-5 w-5" /></span>
                    <div>
                        <h2 class="text-lg font-black tracking-normal text-slate-950">{{ $resourceCategory->name }}</h2>
                        <p class="mt-1 text-sm leading-6 text-slate-600">{{ $resourceCategory->description }}</p>
                    </div>
                </div>
            </a>
        @endforeach
    </section>

    @if ($featured->isNotEmpty())
        <section>
            <div class="mb-4 flex items-center justify-between gap-3">
                <h2 class="text-2xl font-black tracking-normal text-slate-950">Featured resources</h2>
                <a href="{{ route('resources.books') }}" class="text-sm font-black text-emerald-800 hover:underline">Browse books</a>
            </div>
            <div class="grid gap-4 md:grid-cols-2 xl:grid-cols-4">
                @foreach ($featured as $resource)
                    <a href="{{ route('resources.show', $resource->slug) }}" class="app-panel app-panel-hover flex min-h-56 flex-col border-emerald-200 bg-white">
                        <p class="w-fit rounded-full bg-emerald-50 px-3 py-1 text-xs font-black uppercase text-emerald-800">{{ $resource->type }}</p>
                        <h3 class="mt-4 text-xl font-black tracking-normal text-slate-950">{{ $resource->title }}</h3>
                        <p class="mt-2 flex-1 text-sm leading-6 text-slate-600">{{ $resource->excerpt ?: \Illuminate\Support\Str::limit(strip_tags($resource->description), 120) }}</p>
                        <p class="mt-4 text-xs font-bold text-slate-500">{{ $resource->source_name ?: 'MannaRise' }}</p>
                    </a>
                @endforeach
            </div>
        </section>
    @endif

    <section>
        <h2 class="mb-4 text-2xl font-black tracking-normal text-slate-950">Browse all resources</h2>
        <div class="grid gap-4 md:grid-cols-2 xl:grid-cols-3">
            @forelse ($resources as $resource)
                <a href="{{ route('resources.show', $resource->slug) }}" class="app-panel app-panel-hover border-slate-200 bg-white">
                    <div class="flex items-center justify-between gap-3">
                        <span class="rounded-full bg-slate-100 px-3 py-1 text-xs font-black uppercase text-slate-700">{{ $resource->type }}</span>
                        <span class="text-xs font-bold text-slate-500">{{ $resource->category?->name }}</span>
                    </div>
                    <h3 class="mt-4 text-xl font-black tracking-normal text-slate-950">{{ $resource->title }}</h3>
                    <p class="mt-2 text-sm leading-6 text-slate-600">{{ $resource->excerpt ?: \Illuminate\Support\Str::limit(strip_tags($resource->description), 140) }}</p>
                    <p class="mt-4 text-xs font-bold text-emerald-800">{{ $resource->author ?: $resource->source_name }}</p>
                </a>
            @empty
                <div class="app-panel border-dashed border-slate-300 text-sm text-slate-600 md:col-span-2 xl:col-span-3">No resources match your search yet.</div>
            @endforelse
        </div>
        <div class="mt-5">{{ $resources->links() }}</div>
    </section>
</div>
