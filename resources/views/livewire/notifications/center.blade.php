<div>
    <details class="relative">
        <summary class="btn-secondary relative list-none cursor-pointer px-3 [&::-webkit-details-marker]:hidden" title="Alerts">
            <x-ui.icon name="bell" class="h-4 w-4" />
            <span class="hidden sm:inline">Alerts</span>
            @if ($unreadCount > 0)
                <span class="absolute -right-1 -top-1 inline-flex min-h-5 min-w-5 items-center justify-center rounded-full bg-rose-600 px-1.5 text-[0.68rem] font-black text-white">{{ $unreadCount > 9 ? '9+' : $unreadCount }}</span>
            @endif
        </summary>

        <div class="absolute right-0 z-50 mt-2 w-[min(22rem,calc(100vw-1.5rem))] overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-2xl">
            <div class="flex items-center justify-between gap-3 border-b border-slate-100 bg-slate-50 px-4 py-3">
                <div>
                    <p class="font-black tracking-normal text-slate-950">In-app alerts</p>
                    <p class="text-xs font-bold text-slate-500">{{ $unreadCount }} unread</p>
                </div>
                <div class="flex items-center gap-1">
                    @if ($unreadCount > 0)
                        <button type="button" wire:click="markAllAsRead" class="rounded-full border border-slate-200 bg-white px-3 py-1.5 text-xs font-black text-slate-700">Read all</button>
                    @endif
                    <button type="button" wire:click="clearRead" class="rounded-full border border-slate-200 bg-white px-3 py-1.5 text-xs font-black text-slate-700">Clear</button>
                </div>
            </div>

            <div class="max-h-[28rem] overflow-y-auto">
                @forelse ($notifications as $notification)
                    @php
                        $data = $notification->data;
                        $title = $data['title'] ?? 'MannaRise alert';
                        $message = $data['message'] ?? 'New activity was recorded.';
                        $url = $data['url'] ?? null;
                        $icon = $data['icon'] ?? 'bell';
                    @endphp
                    <div class="border-b border-slate-100 px-4 py-3 last:border-0 {{ $notification->read_at ? 'bg-white' : 'bg-emerald-50' }}">
                        <div class="flex gap-3">
                            <span class="mt-0.5 inline-flex h-9 w-9 shrink-0 items-center justify-center rounded-xl bg-white text-emerald-800 shadow-sm">
                                <x-ui.icon :name="$icon" class="h-4 w-4" />
                            </span>
                            <div class="min-w-0 flex-1">
                                <div class="flex items-start justify-between gap-3">
                                    <p class="font-black leading-5 tracking-normal text-slate-950">{{ $title }}</p>
                                    @unless ($notification->read_at)
                                        <span class="mt-1 h-2 w-2 shrink-0 rounded-full bg-emerald-600"></span>
                                    @endunless
                                </div>
                                <p class="mt-1 text-sm leading-5 text-slate-600">{{ $message }}</p>
                                <div class="mt-2 flex flex-wrap items-center gap-2">
                                    @if ($url)
                                        <a href="{{ $url }}" wire:click="markAsRead('{{ $notification->id }}')" class="text-xs font-black text-emerald-800 hover:text-emerald-950">Open</a>
                                    @endif
                                    @unless ($notification->read_at)
                                        <button type="button" wire:click="markAsRead('{{ $notification->id }}')" class="text-xs font-black text-slate-500 hover:text-slate-900">Mark read</button>
                                    @endunless
                                    <span class="text-xs font-bold text-slate-400">{{ $notification->created_at->diffForHumans() }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="p-5 text-sm leading-6 text-slate-600">No alerts yet. Your Bible, journal, prayer, plan, memory, group, and admin activity will appear here.</div>
                @endforelse
            </div>
        </div>
    </details>
</div>
