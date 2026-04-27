<div class="space-y-6">
    <div class="flex flex-col gap-4 md:flex-row md:items-end md:justify-between">
        <div>
            <h1 class="text-3xl font-semibold text-stone-950">Prayer requests</h1>
            <p class="mt-2 text-sm text-stone-600">Review public and private requests.</p>
        </div>
        <input type="search" wire:model.live.debounce.300ms="search" placeholder="Search requests" class="rounded-md border border-stone-300 bg-white px-3 py-2 text-sm outline-none focus:border-emerald-600 focus:ring-2 focus:ring-emerald-100">
    </div>

    <div class="space-y-3">
        @forelse ($requests as $request)
            <article class="rounded-lg border border-stone-200 bg-white p-5 shadow-sm">
                <div class="flex flex-col gap-3 md:flex-row md:items-start md:justify-between">
                    <div>
                        <div class="flex flex-wrap gap-2 text-xs font-semibold uppercase tracking-normal">
                            <span class="rounded-md bg-stone-100 px-2 py-1 text-stone-700">{{ $request->is_public ? 'Public' : 'Private' }}</span>
                            <span class="rounded-md px-2 py-1 {{ $request->is_answered ? 'bg-emerald-50 text-emerald-800' : 'bg-amber-50 text-amber-800' }}">{{ $request->is_answered ? 'Answered' : 'Open' }}</span>
                        </div>
                        <h2 class="mt-3 text-lg font-semibold text-stone-950">{{ $request->title }}</h2>
                        <p class="mt-1 text-sm text-stone-500">{{ $request->name ?: 'Anonymous' }} @if ($request->email) · {{ $request->email }} @endif</p>
                    </div>
                    <div class="flex gap-2">
                        <button type="button" wire:click="toggleAnswered({{ $request->id }})" class="rounded-md border border-stone-300 px-3 py-1.5 text-sm font-semibold text-stone-800 hover:bg-stone-100">{{ $request->is_answered ? 'Reopen' : 'Mark answered' }}</button>
                        <button type="button" wire:click="delete({{ $request->id }})" wire:confirm="Delete this prayer request?" class="rounded-md border border-red-200 px-3 py-1.5 text-sm font-semibold text-red-700 hover:bg-red-50">Delete</button>
                    </div>
                </div>
                <p class="mt-4 text-sm leading-6 text-stone-700">{{ $request->body }}</p>
            </article>
        @empty
            <p class="rounded-lg border border-dashed border-stone-300 bg-white p-5 text-sm text-stone-600">No prayer requests found.</p>
        @endforelse
    </div>

    {{ $requests->links() }}
</div>
