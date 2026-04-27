<div class="space-y-6 sm:space-y-8">
    <div class="app-panel overflow-hidden border-emerald-200 p-0 sm:p-0">
        <div class="color-strip rounded-none">
            <span class="bg-emerald-500"></span>
            <span class="bg-green-500"></span>
            <span class="bg-lime-500"></span>
            <span class="bg-yellow-400"></span>
            <span class="bg-amber-400"></span>
        </div>
        <div class="p-5 sm:p-6">
            <p class="app-eyebrow"><x-ui.icon name="bookmark" class="h-4 w-4" /> 💚 Saved</p>
            <h1 class="mt-3 app-section-title">Favorite devotionals</h1>
            <p class="mt-2 text-sm text-slate-600">Saved readings for return visits.</p>
        </div>
    </div>

    <div class="grid gap-4 md:grid-cols-2 xl:grid-cols-3">
        @forelse ($devotionals as $devotional)
            <article class="app-panel border-t-4 border-t-emerald-500 hover:border-emerald-300 even:border-t-lime-500">
                <p class="inline-flex items-center gap-2 rounded-full bg-emerald-50 px-3 py-1 text-xs font-bold uppercase tracking-normal text-emerald-900">🌿 {{ $devotional->category?->name }}</p>
                <h2 class="mt-3 text-lg font-black tracking-normal text-slate-950">{{ $devotional->title }}</h2>
                <p class="mt-3 text-sm leading-6 text-slate-600">{{ \Illuminate\Support\Str::limit(strip_tags($devotional->content), 145) }}</p>
                <div class="mt-4 flex flex-wrap gap-2">
                    <a href="{{ route('devotionals.show', $devotional->slug) }}" class="btn-primary px-3">Read <x-ui.icon name="chevron-right" class="h-4 w-4" /></a>
                    <button type="button" wire:click="remove({{ $devotional->id }})" class="btn-secondary border-slate-300 px-3">Remove</button>
                </div>
            </article>
        @empty
            <p class="app-panel border-dashed border-slate-300 text-sm text-slate-600 md:col-span-2 xl:col-span-3">You have not saved any devotionals yet.</p>
        @endforelse
    </div>

    {{ $devotionals->links() }}
</div>
