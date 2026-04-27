<div class="space-y-6">
    <div class="flex flex-col gap-4 md:flex-row md:items-end md:justify-between">
        <div>
            <h1 class="text-3xl font-semibold text-stone-950">Devotionals</h1>
            <p class="mt-2 text-sm text-stone-600">Search, filter, and keep growing one reading at a time.</p>
        </div>

        <div class="grid gap-3 sm:grid-cols-2">
            <input type="search" wire:model.live.debounce.300ms="search" placeholder="Search devotionals" class="rounded-md border border-stone-300 bg-white px-3 py-2 text-sm outline-none focus:border-emerald-600 focus:ring-2 focus:ring-emerald-100">
            <select wire:model.live="category" class="rounded-md border border-stone-300 bg-white px-3 py-2 text-sm outline-none focus:border-emerald-600 focus:ring-2 focus:ring-emerald-100">
                <option value="">All topics</option>
                @foreach ($categories as $category)
                    <option value="{{ $category->slug }}">{{ $category->name }}</option>
                @endforeach
            </select>
        </div>
    </div>

    <div class="grid gap-4 md:grid-cols-2 xl:grid-cols-3">
        @forelse ($devotionals as $devotional)
            <article class="rounded-lg border border-stone-200 bg-white p-5 shadow-sm">
                <div class="flex items-center justify-between gap-3">
                    <span class="text-xs font-semibold uppercase tracking-normal text-emerald-800">{{ $devotional->category?->name }}</span>
                    <span class="text-xs text-stone-500">{{ $devotional->reading_time }} min</span>
                </div>
                <h2 class="mt-3 text-xl font-semibold text-stone-950">{{ $devotional->title }}</h2>
                @if ($devotional->bible_reference)
                    <p class="mt-2 text-sm font-medium text-stone-700">{{ $devotional->bible_reference }}</p>
                @endif
                <p class="mt-3 text-sm leading-6 text-stone-600">{{ \Illuminate\Support\Str::limit(strip_tags($devotional->content), 160) }}</p>
                <a href="{{ route('devotionals.show', $devotional->slug) }}" class="mt-4 inline-flex rounded-md bg-emerald-700 px-3 py-2 text-sm font-semibold text-white hover:bg-emerald-800">Read devotional</a>
            </article>
        @empty
            <div class="rounded-lg border border-dashed border-stone-300 bg-white p-6 text-sm text-stone-600 md:col-span-2 xl:col-span-3">
                No devotionals match this search.
            </div>
        @endforelse
    </div>

    {{ $devotionals->links() }}
</div>
