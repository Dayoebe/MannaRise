<div class="space-y-6 sm:space-y-8">
    <div class="app-panel overflow-hidden border-cyan-200 p-0 sm:p-0">
        <div class="color-strip rounded-none">
            <span class="bg-cyan-500"></span>
            <span class="bg-sky-500"></span>
            <span class="bg-blue-500"></span>
            <span class="bg-teal-500"></span>
            <span class="bg-lime-500"></span>
            <span class="bg-orange-500"></span>
            <span class="bg-rose-500"></span>
        </div>
        <div class="flex flex-col gap-5 p-5 sm:p-6 md:flex-row md:items-end md:justify-between">
            <div>
                <p class="app-eyebrow border-cyan-200 bg-cyan-50 text-cyan-900"><x-ui.icon name="library" class="h-4 w-4" /> Public-domain reading</p>
                <h1 class="mt-3 app-section-title">Spiritual library</h1>
                <p class="mt-2 max-w-2xl text-sm leading-6 text-slate-600">Classic Christian texts, spiritual books, and study readings prepared for devotional growth.</p>
            </div>
            <a href="{{ route('bible') }}" class="btn-primary w-full bg-blue-700 hover:bg-blue-800 sm:w-auto"><x-ui.icon name="book-open" class="h-4 w-4" /> Read Bible</a>
        </div>
    </div>

    <label class="block max-w-xl">
        <span class="mb-2 flex items-center gap-2 text-sm font-bold text-slate-700"><x-ui.icon name="search" class="h-4 w-4 text-cyan-700" /> Search library</span>
        <input type="search" wire:model.live.debounce.300ms="search" placeholder="Search books, authors, or descriptions" class="field-input border-cyan-300 focus:border-cyan-600 focus:ring-cyan-100">
    </label>

    @if ($featuredBooks->isNotEmpty())
        <section>
            <div class="mb-4">
                <p class="app-eyebrow border-orange-200 bg-orange-50 text-orange-900"><x-ui.icon name="star" class="h-4 w-4" /> Featured</p>
                <h2 class="mt-3 text-2xl font-black tracking-normal text-slate-950">Featured books</h2>
            </div>
            <div class="grid gap-4 md:grid-cols-3">
                @foreach ($featuredBooks as $book)
                    <a href="{{ route('library.show', $book->slug) }}" class="app-panel border-t-4 border-cyan-200 border-t-cyan-500 hover:border-cyan-400 even:border-t-orange-500">
                        <p class="inline-flex items-center gap-2 rounded-full bg-cyan-50 px-3 py-1 text-xs font-bold uppercase tracking-normal text-cyan-900"><x-ui.icon name="book-open" class="h-4 w-4" /> {{ $book->tradition }}</p>
                        <h3 class="mt-3 text-lg font-black tracking-normal text-slate-950">{{ $book->title }}</h3>
                        <p class="mt-1 text-sm font-bold text-slate-500">{{ $book->author }}</p>
                        <p class="mt-3 text-sm leading-6 text-slate-600">{{ \Illuminate\Support\Str::limit($book->description, 135) }}</p>
                        <p class="mt-4 inline-flex items-center gap-2 rounded-full bg-amber-100 px-3 py-1 text-sm font-bold text-amber-900"><x-ui.icon name="library" class="h-4 w-4" /> {{ $book->chapters_count }} chapters</p>
</a>
                @endforeach
            </div>
        </section>
    @endif

    <section>
        <div class="mb-4">
            <p class="app-eyebrow border-olive-200 bg-olive-50 text-olive-900"><x-ui.icon name="book-open" class="h-4 w-4" /> Complete shelf</p>
            <h2 class="mt-3 text-2xl font-black tracking-normal text-slate-950">All books</h2>
        </div>
        <div class="grid gap-4 md:grid-cols-2 xl:grid-cols-3">
            @forelse ($books as $book)
                <a href="{{ route('library.show', $book->slug) }}" class="app-panel border-slate-200 hover:border-emerald-300 hover:bg-emerald-50">
                    <div class="flex items-start justify-between gap-3">
                        <p class="inline-flex items-center gap-2 rounded-full bg-teal-50 px-3 py-1 text-xs font-bold uppercase tracking-normal text-teal-900"><x-ui.icon name="library" class="h-4 w-4" /> {{ $book->tradition }}</p>
                        @if ($book->is_public_domain)
                            <span class="rounded-full bg-yellow-100 px-2 py-1 text-xs font-bold text-yellow-900">Public domain</span>
                        @endif
                    </div>
                    <h3 class="mt-3 text-lg font-black tracking-normal text-slate-950">{{ $book->title }}</h3>
                    <p class="mt-1 text-sm font-bold text-slate-500">{{ $book->author ?: 'Unknown author' }}</p>
                    <p class="mt-3 text-sm leading-6 text-slate-600">{{ \Illuminate\Support\Str::limit($book->description, 155) }}</p>
                    <p class="mt-4 inline-flex items-center gap-2 text-sm font-bold text-emerald-800">{{ $book->chapters_count }} chapters <x-ui.icon name="chevron-right" class="h-4 w-4" /></p>
</a>
            @empty
                <p class="app-panel border-dashed border-slate-300 text-sm text-slate-600 md:col-span-2 xl:col-span-3">No spiritual books match this search.</p>
            @endforelse
        </div>

        <div class="mt-5">{{ $books->links() }}</div>
    </section>
</div>
