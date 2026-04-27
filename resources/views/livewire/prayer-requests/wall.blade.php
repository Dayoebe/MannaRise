<div class="space-y-6">
    <div class="flex flex-col gap-4 md:flex-row md:items-end md:justify-between">
        <div>
            <h1 class="text-3xl font-semibold text-stone-950">Prayer wall</h1>
            <p class="mt-2 max-w-2xl text-sm leading-6 text-stone-600">Pray with the community, track answered requests, and let people know someone stood with them.</p>
        </div>
        <a href="{{ route('prayer-requests.submit') }}" class="rounded-md bg-emerald-700 px-4 py-2.5 text-sm font-semibold text-white hover:bg-emerald-800">Submit request</a>
    </div>

    <div class="grid gap-4 md:grid-cols-[1fr_auto_auto]">
        <input type="search" wire:model.live.debounce.300ms="search" placeholder="Search prayer requests" class="rounded-md border border-stone-300 bg-white px-3 py-2 text-sm outline-none focus:border-emerald-600 focus:ring-2 focus:ring-emerald-100">
        <select wire:model.live="status" class="rounded-md border border-stone-300 bg-white px-3 py-2 text-sm outline-none focus:border-emerald-600 focus:ring-2 focus:ring-emerald-100">
            <option value="open">Open requests</option>
            <option value="answered">Answered prayers</option>
            <option value="all">All requests</option>
        </select>
        <div class="rounded-md border border-stone-200 bg-white px-3 py-2 text-sm text-stone-600">
            {{ $openCount }} open · {{ $answeredCount }} answered
        </div>
    </div>

    <div class="grid gap-4 md:grid-cols-2 xl:grid-cols-3">
        @forelse ($requests as $request)
            <article class="rounded-lg border border-stone-200 bg-white p-5 shadow-sm">
                <div class="flex items-center justify-between gap-3">
                    <span class="rounded-md px-2 py-1 text-xs font-semibold uppercase tracking-normal {{ $request->is_answered ? 'bg-emerald-50 text-emerald-800' : 'bg-amber-50 text-amber-800' }}">
                        {{ $request->is_answered ? 'Answered' : 'Open' }}
                    </span>
                    <span class="text-xs text-stone-500">{{ $request->created_at->diffForHumans() }}</span>
                </div>
                <h2 class="mt-3 text-lg font-semibold text-stone-950">{{ $request->title }}</h2>
                <p class="mt-2 text-sm text-stone-500">{{ $request->name ?: 'Anonymous' }}</p>
                <p class="mt-3 text-sm leading-6 text-stone-700">{{ $request->body }}</p>
                <div class="mt-5 flex items-center justify-between gap-3">
                    <span class="text-sm font-medium text-stone-600">{{ $request->prayed_count }} prayed</span>
                    <button type="button" wire:click="pray({{ $request->id }})" class="rounded-md border border-emerald-200 px-3 py-2 text-sm font-semibold text-emerald-800 hover:bg-emerald-50">I prayed</button>
                </div>
            </article>
        @empty
            <p class="rounded-lg border border-dashed border-stone-300 bg-white p-6 text-sm text-stone-600 md:col-span-2 xl:col-span-3">No public prayer requests match this view.</p>
        @endforelse
    </div>

    {{ $requests->links() }}
</div>
