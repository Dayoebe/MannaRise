<div class="space-y-6 sm:space-y-8">
    <div class="app-panel overflow-hidden border-fuchsia-200 p-0 sm:p-0">
        <div class="color-strip rounded-none">
            <span class="bg-fuchsia-500"></span>
            <span class="bg-pink-500"></span>
            <span class="bg-rose-500"></span>
            <span class="bg-orange-500"></span>
            <span class="bg-emerald-500"></span>
        </div>
        <div class="flex flex-col gap-4 p-5 sm:p-6 md:flex-row md:items-end md:justify-between">
            <div>
                <p class="app-eyebrow border-fuchsia-200 bg-fuchsia-50 text-fuchsia-900"><x-ui.icon name="message-circle" class="h-4 w-4" /> 🌈 Moderation</p>
                <h1 class="mt-3 app-section-title">Moderate testimonies</h1>
                <p class="mt-2 text-sm text-slate-600">Approve testimonies before they appear publicly.</p>
            </div>
            <select wire:model.live="filter" class="field-input border-fuchsia-300 focus:border-fuchsia-600 focus:ring-fuchsia-100 md:max-w-xs">
                <option value="pending">Pending</option>
                <option value="approved">Approved</option>
                <option value="all">All</option>
            </select>
        </div>
    </div>

    <div class="space-y-3">
        @forelse ($testimonies as $testimony)
            <article class="app-panel border-t-4 {{ $testimony->is_approved ? 'border-t-emerald-500' : 'border-t-amber-400' }}">
                <div class="flex flex-col gap-3 md:flex-row md:items-start md:justify-between">
                    <div>
                        <span class="rounded-full px-3 py-1 text-xs font-bold uppercase tracking-normal {{ $testimony->is_approved ? 'bg-emerald-50 text-emerald-900' : 'bg-amber-50 text-amber-900' }}">{{ $testimony->is_approved ? 'Approved ✅' : 'Pending 🌟' }}</span>
                        <h2 class="mt-3 text-lg font-black tracking-normal text-slate-950">{{ $testimony->title }}</h2>
                        <p class="mt-1 text-sm font-bold text-slate-500">{{ $testimony->is_anonymous ? 'Anonymous' : ($testimony->name ?: 'MannaRise reader') }}</p>
                    </div>
                    <div class="flex flex-wrap gap-2">
                        <button type="button" wire:click="toggleApproval({{ $testimony->id }})" class="rounded-full border border-slate-300 bg-white px-3 py-1.5 text-sm font-bold text-slate-800 hover:bg-slate-50">{{ $testimony->is_approved ? 'Unapprove' : 'Approve' }}</button>
                        <button type="button" wire:click="delete({{ $testimony->id }})" wire:confirm="Delete this testimony?" class="rounded-full border border-red-200 bg-white px-3 py-1.5 text-sm font-bold text-red-700 hover:bg-red-50">Delete</button>
                    </div>
                </div>
                <p class="mt-4 text-sm leading-6 text-slate-700">{{ $testimony->body }}</p>
            </article>
        @empty
            <p class="app-panel border-dashed border-slate-300 text-sm text-slate-600">No testimonies found.</p>
        @endforelse
    </div>

    {{ $testimonies->links() }}
</div>
