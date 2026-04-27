<div class="space-y-6">
    <div class="flex flex-col gap-4 md:flex-row md:items-end md:justify-between">
        <div>
            <h1 class="text-3xl font-semibold text-stone-950">Moderate testimonies</h1>
            <p class="mt-2 text-sm text-stone-600">Approve testimonies before they appear publicly.</p>
        </div>
        <select wire:model.live="filter" class="rounded-md border border-stone-300 bg-white px-3 py-2 text-sm outline-none focus:border-emerald-600 focus:ring-2 focus:ring-emerald-100">
            <option value="pending">Pending</option>
            <option value="approved">Approved</option>
            <option value="all">All</option>
        </select>
    </div>

    <div class="space-y-3">
        @forelse ($testimonies as $testimony)
            <article class="rounded-lg border border-stone-200 bg-white p-5 shadow-sm">
                <div class="flex flex-col gap-3 md:flex-row md:items-start md:justify-between">
                    <div>
                        <span class="rounded-md px-2 py-1 text-xs font-semibold uppercase tracking-normal {{ $testimony->is_approved ? 'bg-emerald-50 text-emerald-800' : 'bg-amber-50 text-amber-800' }}">{{ $testimony->is_approved ? 'Approved' : 'Pending' }}</span>
                        <h2 class="mt-3 text-lg font-semibold text-stone-950">{{ $testimony->title }}</h2>
                        <p class="mt-1 text-sm text-stone-500">{{ $testimony->is_anonymous ? 'Anonymous' : ($testimony->name ?: 'MannaRise reader') }}</p>
                    </div>
                    <div class="flex gap-2">
                        <button type="button" wire:click="toggleApproval({{ $testimony->id }})" class="rounded-md border border-stone-300 px-3 py-1.5 text-sm font-semibold text-stone-800 hover:bg-stone-100">{{ $testimony->is_approved ? 'Unapprove' : 'Approve' }}</button>
                        <button type="button" wire:click="delete({{ $testimony->id }})" wire:confirm="Delete this testimony?" class="rounded-md border border-red-200 px-3 py-1.5 text-sm font-semibold text-red-700 hover:bg-red-50">Delete</button>
                    </div>
                </div>
                <p class="mt-4 text-sm leading-6 text-stone-700">{{ $testimony->body }}</p>
            </article>
        @empty
            <p class="rounded-lg border border-dashed border-stone-300 bg-white p-5 text-sm text-stone-600">No testimonies found.</p>
        @endforelse
    </div>

    {{ $testimonies->links() }}
</div>
