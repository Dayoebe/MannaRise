<div class="space-y-6 sm:space-y-8">
    <div class="app-panel overflow-hidden border-rose-200 p-0 sm:p-0">
        <div class="color-strip rounded-none">
            <span class="bg-rose-500"></span>
            <span class="bg-pink-500"></span>
            <span class="bg-fuchsia-500"></span>
            <span class="bg-amber-400"></span>
            <span class="bg-emerald-500"></span>
        </div>
        <div class="flex flex-col gap-4 p-5 sm:p-6 md:flex-row md:items-end md:justify-between">
            <div>
                <p class="app-eyebrow border-rose-200 bg-rose-50 text-rose-900"><x-ui.icon name="heart" class="h-4 w-4" /> 🙏 Moderation</p>
                <h1 class="mt-3 app-section-title">Prayer requests</h1>
                <p class="mt-2 text-sm text-slate-600">Review public and private requests.</p>
            </div>
            <input type="search" wire:model.live.debounce.300ms="search" placeholder="Search requests" class="field-input border-rose-300 focus:border-rose-600 focus:ring-rose-100 md:max-w-sm">
        </div>
    </div>

    <div class="space-y-3">
        @forelse ($requests as $request)
            <article class="app-panel border-t-4 {{ $request->is_answered ? 'border-t-emerald-500' : 'border-t-amber-400' }}">
                <div class="flex flex-col gap-3 md:flex-row md:items-start md:justify-between">
                    <div>
                        <div class="flex flex-wrap gap-2 text-xs font-bold uppercase tracking-normal">
                            <span class="rounded-full bg-slate-100 px-3 py-1 text-slate-700">{{ $request->is_public ? 'Public' : 'Private' }}</span>
                            <span class="rounded-full px-3 py-1 {{ $request->is_answered ? 'bg-emerald-50 text-emerald-900' : 'bg-amber-50 text-amber-900' }}">{{ $request->is_answered ? 'Answered ✅' : 'Open 🙏' }}</span>
                        </div>
                        <h2 class="mt-3 text-lg font-black tracking-normal text-slate-950">{{ $request->title }}</h2>
                        <p class="mt-1 text-sm font-bold text-slate-500">{{ $request->name ?: 'Anonymous' }} @if ($request->email) · {{ $request->email }} @endif</p>
                    </div>
                    <div class="flex flex-wrap gap-2">
                        <button type="button" wire:click="toggleAnswered({{ $request->id }})" class="rounded-full border border-slate-300 bg-white px-3 py-1.5 text-sm font-bold text-slate-800 hover:bg-slate-50">{{ $request->is_answered ? 'Reopen' : 'Mark answered' }}</button>
                        <button type="button" wire:click="delete({{ $request->id }})" wire:confirm="Delete this prayer request?" class="rounded-full border border-red-200 bg-white px-3 py-1.5 text-sm font-bold text-red-700 hover:bg-red-50">Delete</button>
                    </div>
                </div>
                <p class="mt-4 text-sm leading-6 text-slate-700">{{ $request->body }}</p>
            </article>
        @empty
            <p class="app-panel border-dashed border-slate-300 text-sm text-slate-600">No prayer requests found.</p>
        @endforelse
    </div>

    {{ $requests->links() }}
</div>
