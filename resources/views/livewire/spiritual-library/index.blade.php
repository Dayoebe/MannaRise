<div class="space-y-6">
    <div class="flex flex-col gap-4 md:flex-row md:items-end md:justify-between">
        <div>
            <p class="text-sm font-semibold uppercase tracking-normal text-emerald-800">Public-domain reading</p>
            <h1 class="mt-2 text-3xl font-semibold text-stone-950">Spiritual library</h1>
            <p class="mt-2 max-w-2xl text-sm leading-6 text-stone-600">Classic Christian texts, spiritual books, and study readings prepared for devotional growth.</p>
        </div>
        <a href="{{ route('bible') }}" class="rounded-md border border-stone-300 bg-white px-4 py-2.5 text-sm font-semibold text-stone-800 hover:bg-stone-100">Read Bible</a>
    </div>

    <input type="search" wire:model.live.debounce.300ms="search" placeholder="Search books, authors, or descriptions" class="w-full rounded-md border border-stone-300 bg-white px-3 py-2 text-sm outline-none focus:border-emerald-600 focus:ring-2 focus:ring-emerald-100 md:max-w-lg">

    @if ($featuredBooks->isNotEmpty())
        <section>
            <h2 class="mb-4 text-xl font-semibold text-stone-950">Featured books</h2>
            <div class="grid gap-4 md:grid-cols-3">
                @foreach ($featuredBooks as $book)
                    <a href="{{ route('library.show', $book->slug) }}" class="rounded-lg border border-emerald-200 bg-white p-5 shadow-sm hover:border-emerald-400">
                        <p class="text-xs font-semibold uppercase tracking-normal text-emerald-800">{{ $book->tradition }}</p>
                        <h3 class="mt-2 text-lg font-semibold text-stone-950">{{ $book->title }}</h3>
                        <p class="mt-1 text-sm text-stone-500">{{ $book->author }}</p>
                        <p class="mt-3 text-sm leading-6 text-stone-600">{{ \Illuminate\Support\Str::limit($book->description, 130) }}</p>
                        <p class="mt-4 text-sm font-medium text-emerald-800">{{ $book->chapters_count }} chapters</p>
                    </a>
                @endforeach
            </div>
        </section>
    @endif

    <section>
        <h2 class="mb-4 text-xl font-semibold text-stone-950">All books</h2>
        <div class="grid gap-4 md:grid-cols-2 xl:grid-cols-3">
            @forelse ($books as $book)
                <a href="{{ route('library.show', $book->slug) }}" class="rounded-lg border border-stone-200 bg-white p-5 shadow-sm hover:border-emerald-300">
                    <div class="flex items-start justify-between gap-3">
                        <p class="text-xs font-semibold uppercase tracking-normal text-emerald-800">{{ $book->tradition }}</p>
                        @if ($book->is_public_domain)
                            <span class="rounded-md bg-stone-100 px-2 py-1 text-xs font-medium text-stone-600">Public domain</span>
                        @endif
                    </div>
                    <h3 class="mt-2 text-lg font-semibold text-stone-950">{{ $book->title }}</h3>
                    <p class="mt-1 text-sm text-stone-500">{{ $book->author ?: 'Unknown author' }}</p>
                    <p class="mt-3 text-sm leading-6 text-stone-600">{{ \Illuminate\Support\Str::limit($book->description, 150) }}</p>
                    <p class="mt-4 text-sm font-medium text-emerald-800">{{ $book->chapters_count }} chapters</p>
                </a>
            @empty
                <p class="rounded-lg border border-dashed border-stone-300 bg-white p-6 text-sm text-stone-600 md:col-span-2 xl:col-span-3">No spiritual books match this search.</p>
            @endforelse
        </div>

        <div class="mt-5">{{ $books->links() }}</div>
    </section>
</div>
