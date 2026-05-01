<div class="space-y-6 sm:space-y-8">
    <section class="app-panel border-emerald-200 bg-white">
        <div class="flex flex-col gap-4 md:flex-row md:items-end md:justify-between">
            <div>
                <p class="app-eyebrow border-emerald-200 bg-emerald-50 text-emerald-900"><x-ui.icon name="library" class="h-4 w-4" /> Books</p>
                <h1 class="mt-3 app-section-title">Free books and study resources</h1>
                <p class="mt-2 text-sm leading-6 text-slate-600">Public-domain Christian and educational books from local curation and supported open APIs.</p>
            </div>
            @if (auth()->user()?->hasAdminAccess())
                <button type="button" wire:click="importExternal" class="btn-primary bg-emerald-700 hover:bg-emerald-800"><x-ui.icon name="download" class="h-4 w-4" /> Refresh free books</button>
            @endif
        </div>
        <input type="search" wire:model.live.debounce.350ms="search" placeholder="Search books or authors" class="field-input mt-5 border-emerald-300 focus:border-emerald-700 focus:ring-emerald-100">
    </section>

    <div class="grid gap-4 md:grid-cols-2 xl:grid-cols-3">
        @forelse ($books as $book)
            <a href="{{ route('resources.show', $book->slug) }}" class="app-panel app-panel-hover border-slate-200 bg-white">
                @if ($book->thumbnail_url)
                    <img src="{{ $book->thumbnail_url }}" alt="" class="mb-4 h-44 w-full rounded-xl object-cover">
                @endif
                <p class="text-xs font-black uppercase text-emerald-800">{{ $book->source_name ?: 'Book' }}</p>
                <h2 class="mt-2 text-xl font-black tracking-normal text-slate-950">{{ $book->title }}</h2>
                <p class="mt-2 text-sm font-bold text-slate-500">{{ $book->author }}</p>
                <p class="mt-3 text-sm leading-6 text-slate-600">{{ $book->excerpt ?: \Illuminate\Support\Str::limit(strip_tags($book->description), 130) }}</p>
            </a>
        @empty
            <div class="app-panel border-dashed border-slate-300 text-sm text-slate-600 md:col-span-2 xl:col-span-3">No books yet. An admin can sync public-domain books from supported providers.</div>
        @endforelse
    </div>
    <div>{{ $books->links() }}</div>
</div>
