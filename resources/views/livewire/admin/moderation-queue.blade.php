<div class="space-y-6 sm:space-y-8">
    <section class="app-panel overflow-hidden border-rose-200 p-0 sm:p-0">
        <div class="color-strip rounded-none">
            <span class="bg-rose-500"></span>
            <span class="bg-fuchsia-500"></span>
            <span class="bg-amber-400"></span>
            <span class="bg-emerald-500"></span>
            <span class="bg-sky-500"></span>
        </div>
        <div class="grid gap-4 p-5 sm:p-6 lg:grid-cols-[minmax(0,1fr)_minmax(18rem,0.75fr)] lg:items-end">
            <div>
                <p class="app-eyebrow border-rose-200 bg-rose-50 text-rose-900"><x-ui.icon name="shield" class="h-4 w-4" /> Moderation queue</p>
                <h1 class="mt-3 app-section-title">Prayers and testimonies</h1>
                <p class="mt-2 max-w-2xl text-sm leading-6 text-slate-600">Review community submissions before they appear in public areas.</p>
            </div>
            <div class="grid gap-2 sm:grid-cols-3 lg:grid-cols-1">
                <div class="rounded-xl border border-amber-200 bg-amber-50 px-4 py-3 text-sm font-black text-amber-900">{{ $counts['pending'] }} pending</div>
                <div class="rounded-xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm font-black text-emerald-900">{{ $counts['approved'] }} approved</div>
                <div class="rounded-xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm font-black text-slate-700">{{ $counts['rejected'] }} rejected</div>
            </div>
        </div>
    </section>

    <section class="app-panel border-slate-200">
        <div class="grid gap-3 md:grid-cols-[minmax(0,1fr)_minmax(14rem,0.35fr)]">
            <input type="search" wire:model.live.debounce.300ms="search" placeholder="Search queue" class="field-input border-slate-300 focus:border-rose-600 focus:ring-rose-100">
            <select wire:model.live="status" class="field-input border-slate-300 focus:border-rose-600 focus:ring-rose-100">
                <option value="pending">Pending</option>
                <option value="approved">Approved</option>
                <option value="rejected">Rejected</option>
                <option value="all">All statuses</option>
            </select>
        </div>
    </section>

    <section class="grid gap-6 xl:grid-cols-2">
        <div>
            <div class="mb-4 flex items-center justify-between gap-3">
                <h2 class="flex items-center gap-2 text-xl font-black tracking-normal text-slate-950"><x-ui.icon name="heart" class="h-5 w-5 text-rose-800" /> Prayer requests</h2>
                <a href="{{ route('admin.prayer-requests') }}" class="text-sm font-bold text-rose-800 hover:underline">Open full list</a>
            </div>

            <div class="space-y-3">
                @forelse ($prayers as $prayer)
                    <article class="app-panel border-t-4 {{ $prayer->moderation_status === 'approved' ? 'border-t-emerald-500' : ($prayer->moderation_status === 'rejected' ? 'border-t-slate-400' : 'border-t-amber-400') }}">
                        <div class="flex flex-col gap-3 sm:flex-row sm:items-start sm:justify-between">
                            <div>
                                <div class="flex flex-wrap gap-2">
                                    <span class="rounded-full bg-rose-50 px-3 py-1 text-xs font-black uppercase tracking-normal text-rose-900">Prayer</span>
                                    <span class="rounded-full bg-slate-100 px-3 py-1 text-xs font-black uppercase tracking-normal text-slate-700">{{ ucfirst($prayer->moderation_status) }}</span>
                                    @if ($prayer->room)
                                        <span class="rounded-full bg-sky-50 px-3 py-1 text-xs font-black uppercase tracking-normal text-sky-900">{{ $prayer->room->name }}</span>
                                    @endif
                                </div>
                                <h3 class="mt-3 font-black tracking-normal text-slate-950">{{ $prayer->title }}</h3>
                                <p class="mt-1 text-sm font-bold text-slate-500">{{ $prayer->name ?: 'Anonymous' }} @if ($prayer->email) &middot; {{ $prayer->email }} @endif</p>
                            </div>
                            <div class="flex flex-wrap gap-2">
                                <button type="button" wire:click="approvePrayer({{ $prayer->id }})" class="rounded-full border border-emerald-200 bg-white px-3 py-1.5 text-sm font-bold text-emerald-800 hover:bg-emerald-50">Approve</button>
                                <button type="button" wire:click="queuePrayer({{ $prayer->id }})" class="rounded-full border border-amber-200 bg-white px-3 py-1.5 text-sm font-bold text-amber-800 hover:bg-amber-50">Queue</button>
                                <button type="button" wire:click="rejectPrayer({{ $prayer->id }})" class="rounded-full border border-red-200 bg-white px-3 py-1.5 text-sm font-bold text-red-700 hover:bg-red-50">Reject</button>
                            </div>
                        </div>
                        <p class="mt-4 text-sm leading-6 text-slate-700">{{ $prayer->body }}</p>
                    </article>
                @empty
                    <p class="app-panel border-dashed border-slate-300 text-sm text-slate-600">No prayer requests match this view.</p>
                @endforelse
            </div>
        </div>

        <div>
            <div class="mb-4 flex items-center justify-between gap-3">
                <h2 class="flex items-center gap-2 text-xl font-black tracking-normal text-slate-950"><x-ui.icon name="message-circle" class="h-5 w-5 text-fuchsia-800" /> Testimonies</h2>
                <a href="{{ route('admin.testimonies') }}" class="text-sm font-bold text-fuchsia-800 hover:underline">Open full list</a>
            </div>

            <div class="space-y-3">
                @forelse ($testimonies as $testimony)
                    <article class="app-panel border-t-4 {{ $testimony->moderation_status === 'approved' ? 'border-t-emerald-500' : ($testimony->moderation_status === 'rejected' ? 'border-t-slate-400' : 'border-t-amber-400') }}">
                        <div class="flex flex-col gap-3 sm:flex-row sm:items-start sm:justify-between">
                            <div>
                                <div class="flex flex-wrap gap-2">
                                    <span class="rounded-full bg-fuchsia-50 px-3 py-1 text-xs font-black uppercase tracking-normal text-fuchsia-900">Testimony</span>
                                    <span class="rounded-full bg-slate-100 px-3 py-1 text-xs font-black uppercase tracking-normal text-slate-700">{{ ucfirst($testimony->moderation_status) }}</span>
                                    <span class="rounded-full bg-amber-50 px-3 py-1 text-xs font-black uppercase tracking-normal text-amber-900">{{ $testimony->categoryLabel() }}</span>
                                </div>
                                <h3 class="mt-3 font-black tracking-normal text-slate-950">{{ $testimony->title }}</h3>
                                <p class="mt-1 text-sm font-bold text-slate-500">{{ $testimony->is_anonymous ? 'Anonymous' : ($testimony->name ?: 'MannaRise reader') }}</p>
                            </div>
                            <div class="flex flex-wrap gap-2">
                                <button type="button" wire:click="approveTestimony({{ $testimony->id }})" class="rounded-full border border-emerald-200 bg-white px-3 py-1.5 text-sm font-bold text-emerald-800 hover:bg-emerald-50">Approve</button>
                                <button type="button" wire:click="queueTestimony({{ $testimony->id }})" class="rounded-full border border-amber-200 bg-white px-3 py-1.5 text-sm font-bold text-amber-800 hover:bg-amber-50">Queue</button>
                                <button type="button" wire:click="rejectTestimony({{ $testimony->id }})" class="rounded-full border border-red-200 bg-white px-3 py-1.5 text-sm font-bold text-red-700 hover:bg-red-50">Reject</button>
                            </div>
                        </div>
                        <p class="mt-4 text-sm leading-6 text-slate-700">{{ $testimony->body }}</p>
                    </article>
                @empty
                    <p class="app-panel border-dashed border-slate-300 text-sm text-slate-600">No testimonies match this view.</p>
                @endforelse
            </div>
        </div>
    </section>
</div>
