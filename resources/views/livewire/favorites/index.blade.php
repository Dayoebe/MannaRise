<div class="space-y-6">
    <div>
        <h1 class="text-3xl font-semibold text-stone-950">Favorite devotionals</h1>
        <p class="mt-2 text-sm text-stone-600">Saved readings for return visits.</p>
    </div>

    <div class="grid gap-4 md:grid-cols-2 xl:grid-cols-3">
        @forelse ($devotionals as $devotional)
            <article class="rounded-lg border border-stone-200 bg-white p-5 shadow-sm">
                <p class="text-xs font-semibold uppercase tracking-normal text-emerald-800">{{ $devotional->category?->name }}</p>
                <h2 class="mt-2 text-lg font-semibold text-stone-950">{{ $devotional->title }}</h2>
                <p class="mt-3 text-sm leading-6 text-stone-600">{{ \Illuminate\Support\Str::limit(strip_tags($devotional->content), 140) }}</p>
                <div class="mt-4 flex flex-wrap gap-2">
                    <a href="{{ route('devotionals.show', $devotional->slug) }}" class="rounded-md bg-emerald-700 px-3 py-2 text-sm font-semibold text-white hover:bg-emerald-800">Read</a>
                    <button type="button" wire:click="remove({{ $devotional->id }})" class="rounded-md border border-stone-300 px-3 py-2 text-sm font-semibold text-stone-800 hover:bg-stone-100">Remove</button>
                </div>
            </article>
        @empty
            <p class="rounded-lg border border-dashed border-stone-300 bg-white p-5 text-sm text-stone-600 md:col-span-2 xl:col-span-3">You have not saved any devotionals yet.</p>
        @endforelse
    </div>

    {{ $devotionals->links() }}
</div>
