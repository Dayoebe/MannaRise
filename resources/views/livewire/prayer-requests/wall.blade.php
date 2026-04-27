<div class="space-y-6 sm:space-y-8">
    <div class="app-panel overflow-hidden border-rose-200 p-0 sm:p-0">
        <div class="color-strip rounded-none">
            <span class="bg-rose-500"></span>
            <span class="bg-pink-500"></span>
            <span class="bg-fuchsia-500"></span>
            <span class="bg-purple-500"></span>
            <span class="bg-emerald-500"></span>
            <span class="bg-amber-400"></span>
        </div>
        <div class="flex flex-col gap-4 p-5 sm:p-6 md:flex-row md:items-end md:justify-between">
            <div>
                <p class="app-eyebrow border-rose-200 bg-rose-50 text-rose-900"><x-ui.icon name="heart" class="h-4 w-4" /> Prayer wall</p>
                <h1 class="mt-3 app-section-title">Prayer wall</h1>
                <p class="mt-2 max-w-2xl text-sm leading-6 text-slate-600">Pray with the community, track answered requests, and let people know someone stood with them.</p>
            </div>
            <a href="{{ route('prayer-requests.submit') }}" class="btn-primary w-full bg-rose-700 hover:bg-rose-800 sm:w-auto"><x-ui.icon name="send" class="h-4 w-4" /> Submit request</a>
        </div>
    </div>

    <div class="grid gap-3 md:grid-cols-[1fr_minmax(12rem,auto)_auto]">
        <label class="block">
            <span class="mb-2 flex items-center gap-2 text-sm font-bold text-slate-700"><x-ui.icon name="search" class="h-4 w-4 text-rose-700" /> Search</span>
            <input type="search" wire:model.live.debounce.300ms="search" placeholder="Search prayer requests" class="field-input border-rose-300 focus:border-rose-600 focus:ring-rose-100">
        </label>
        <label class="block">
            <span class="mb-2 flex items-center gap-2 text-sm font-bold text-slate-700"><x-ui.icon name="star" class="h-4 w-4 text-rose-700" /> Status</span>
            <select wire:model.live="status" class="field-input border-rose-300 focus:border-rose-600 focus:ring-rose-100">
                <option value="open">Open requests</option>
                <option value="answered">Answered prayers</option>
                <option value="all">All requests</option>
            </select>
        </label>
        <div class="app-surface flex min-h-11 items-center rounded-xl border-amber-200 bg-amber-50 px-4 py-2 text-sm font-bold text-amber-900 md:self-end">
            {{ $openCount }} open · {{ $answeredCount }} answered
        </div>
    </div>

    <div class="public-card-grid">
        @forelse ($requests as $request)
            <article class="app-panel public-card border-t-4 {{ $request->is_answered ? 'border-t-emerald-500' : 'border-t-amber-400' }}">
                <div class="flex flex-wrap items-center justify-between gap-3">
                    <span class="rounded-full px-3 py-1 text-xs font-bold uppercase tracking-normal {{ $request->is_answered ? 'bg-emerald-50 text-emerald-900' : 'bg-amber-50 text-amber-900' }}">
                        {{ $request->is_answered ? 'Answered' : 'Open' }}
                    </span>
                    <span class="text-xs font-bold text-slate-500">{{ $request->created_at->diffForHumans() }}</span>
                </div>
                <h2 class="mt-3 text-lg font-black tracking-normal text-slate-950">{{ $request->title }}</h2>
                <p class="mt-2 text-sm font-bold text-slate-500">{{ $request->name ?: 'Anonymous' }}</p>
                <p class="mt-3 flex-1 text-sm leading-6 text-slate-700">{{ $request->body }}</p>
                <div class="mt-5 flex flex-wrap items-center justify-between gap-3">
                    <span class="text-sm font-bold text-rose-800">{{ $request->prayed_count }} prayed</span>
                    <button type="button" wire:click="pray({{ $request->id }})" class="btn-secondary border-rose-200 px-3 hover:bg-rose-50"><x-ui.icon name="heart" class="h-4 w-4" /> I prayed</button>
                </div>
            </article>
        @empty
            <p class="app-panel border-dashed border-slate-300 text-sm text-slate-600 md:col-span-2 xl:col-span-3">No public prayer requests match this view.</p>
        @endforelse
    </div>

    {{ $requests->links() }}
</div>
