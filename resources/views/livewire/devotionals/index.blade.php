<div class="space-y-6 sm:space-y-8">
    <div wire:loading.flex class="loading-hint items-center gap-2">
        <x-ui.icon name="search" class="h-4 w-4 animate-pulse" /> Refreshing devotionals...
    </div>

    <div class="page-hero border-amber-200">
        <div class="color-strip rounded-none">
            <span class="bg-amber-400"></span>
            <span class="bg-yellow-400"></span>
            <span class="bg-lime-500"></span>
            <span class="bg-emerald-500"></span>
            <span class="bg-teal-500"></span>
            <span class="bg-sky-500"></span>
            <span class="bg-pink-500"></span>
        </div>
        <div class="page-hero-body">
            <div>
                <p class="app-eyebrow border-amber-200 bg-amber-50 text-amber-900"><x-ui.icon name="sparkles" class="h-4 w-4" /> Daily readings</p>
                <h1 class="mt-3 app-section-title">Devotionals</h1>
                <p class="mt-2 max-w-2xl text-sm leading-6 text-slate-600">Search by topic, scripture reference, or message. Save what blesses you and build consistency one reading at a time.</p>
            </div>

            <div class="grid gap-3 sm:grid-cols-2">
                <label class="block">
                    <span class="mb-2 flex items-center gap-2 text-sm font-bold text-slate-700"><x-ui.icon name="search" class="h-4 w-4 text-amber-700" /> Search</span>
                    <input type="search" wire:model.live.debounce.300ms="search" placeholder="Search devotionals" class="field-input border-amber-300 focus:border-amber-600 focus:ring-amber-100">
                </label>
                <label class="block">
                    <span class="mb-2 flex items-center gap-2 text-sm font-bold text-slate-700"><x-ui.icon name="bookmark" class="h-4 w-4 text-amber-700" /> Topic</span>
                    <select wire:model.live="category" class="field-input border-amber-300 focus:border-amber-600 focus:ring-amber-100">
                        <option value="">All topics</option>
                        @foreach ($categories as $category)
                            <option value="{{ $category->slug }}">{{ $category->name }}</option>
                        @endforeach
                    </select>
                </label>
            </div>
        </div>
    </div>

    <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
        <p class="text-sm font-bold text-slate-600">{{ $devotionals->total() }} devotionals found</p>
        <a href="{{ route('prayer-requests.submit') }}" class="btn-secondary w-full sm:w-auto"><x-ui.icon name="send" class="h-4 w-4" /> Need prayer?</a>
    </div>

    <div class="public-card-grid">
        @forelse ($devotionals as $devotional)
            <article class="app-panel app-panel-hover public-card border-t-4 border-t-amber-400 hover:border-amber-300 even:border-t-emerald-500">
                <div class="flex flex-wrap items-center justify-between gap-3">
                    <span class="status-pill bg-emerald-50 text-emerald-900"><x-ui.icon name="bookmark" class="h-4 w-4" /> {{ $devotional->category?->name }}</span>
                    <span class="rounded-full bg-sky-50 px-2.5 py-1 text-xs font-bold text-sky-900">{{ $devotional->reading_time }} min</span>
                </div>
                <h2 class="mt-3 text-xl font-black tracking-normal text-slate-950">{{ $devotional->title }}</h2>
                @if ($devotional->bible_reference)
                    <p class="mt-2 inline-flex items-center gap-2 text-sm font-bold text-olive-800"><x-ui.icon name="book-open" class="h-4 w-4" /> {{ $devotional->bible_reference }}</p>
                @endif
                <p class="mt-3 flex-1 text-sm leading-6 text-slate-600">{{ \Illuminate\Support\Str::limit(strip_tags($devotional->content), 165) }}</p>
                <a href="{{ route('devotionals.show', $devotional->slug) }}" class="mt-4 btn-primary w-full px-3 sm:w-fit">Read devotional <x-ui.icon name="chevron-right" class="h-4 w-4" /></a>
            </article>
        @empty
            <div class="md:col-span-2 xl:col-span-3">
                <x-ui.empty-state title="No devotionals found" message="Try another search term or clear the topic filter. Published devotionals will appear here." action-label="Browse all" :action-href="route('devotionals.index')" />
            </div>
        @endforelse
    </div>

    <div class="mt-2">{{ $devotionals->links() }}</div>
</div>
