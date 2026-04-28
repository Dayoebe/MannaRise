<div class="space-y-6 sm:space-y-8">
    <section class="app-panel overflow-hidden border-rose-200 p-0 sm:p-0">
        <div class="color-strip rounded-none">
            <span class="bg-rose-500"></span>
            <span class="bg-emerald-500"></span>
            <span class="bg-amber-400"></span>
            <span class="bg-sky-500"></span>
            <span class="bg-fuchsia-500"></span>
            <span class="bg-indigo-500"></span>
        </div>
        <div class="grid gap-5 p-5 sm:p-6 lg:grid-cols-[minmax(0,1fr)_minmax(18rem,26rem)] lg:items-end">
            <div>
                <p class="app-eyebrow border-rose-200 bg-rose-50 text-rose-900"><x-ui.icon name="users" class="h-4 w-4" /> Prayer rooms</p>
                <h1 class="mt-3 app-section-title">Focused prayer rooms</h1>
                <p class="mt-2 max-w-2xl text-sm leading-6 text-slate-600">Join a focused room, pray with others, keep a prayer streak, and share answered-prayer updates when God moves.</p>
            </div>
            <div class="app-surface grid gap-3 border-rose-100 bg-rose-50 p-4 sm:grid-cols-2">
                <div>
                    <p class="text-xs font-bold uppercase tracking-normal text-rose-800">Rooms</p>
                    <p class="mt-1 text-3xl font-black tracking-normal text-slate-950">{{ $rooms->count() }}</p>
                </div>
                <div>
                    <p class="text-xs font-bold uppercase tracking-normal text-rose-800">Prayed today</p>
                    <p class="mt-1 text-3xl font-black tracking-normal text-slate-950">{{ $todayPrayerCount }}</p>
                </div>
            </div>
        </div>
    </section>

    <div class="public-card-grid">
        @foreach ($rooms as $room)
            @php
                $palette = match ($room->accent) {
                    'emerald' => [
                        'card' => 'border-emerald-200',
                        'top' => 'border-t-emerald-500',
                        'badge' => 'border-emerald-200 bg-emerald-50 text-emerald-900',
                        'button' => 'bg-emerald-700 hover:bg-emerald-800',
                    ],
                    'amber' => [
                        'card' => 'border-amber-200',
                        'top' => 'border-t-amber-400',
                        'badge' => 'border-amber-200 bg-amber-50 text-amber-900',
                        'button' => 'bg-amber-600 text-slate-950 hover:bg-amber-500',
                    ],
                    'sky' => [
                        'card' => 'border-sky-200',
                        'top' => 'border-t-sky-500',
                        'badge' => 'border-sky-200 bg-sky-50 text-sky-900',
                        'button' => 'bg-sky-700 hover:bg-sky-800',
                    ],
                    'fuchsia' => [
                        'card' => 'border-fuchsia-200',
                        'top' => 'border-t-fuchsia-500',
                        'badge' => 'border-fuchsia-200 bg-fuchsia-50 text-fuchsia-900',
                        'button' => 'bg-fuchsia-700 hover:bg-fuchsia-800',
                    ],
                    'indigo' => [
                        'card' => 'border-indigo-200',
                        'top' => 'border-t-indigo-500',
                        'badge' => 'border-indigo-200 bg-indigo-50 text-indigo-900',
                        'button' => 'bg-indigo-700 hover:bg-indigo-800',
                    ],
                    default => [
                        'card' => 'border-rose-200',
                        'top' => 'border-t-rose-500',
                        'badge' => 'border-rose-200 bg-rose-50 text-rose-900',
                        'button' => 'bg-rose-700 hover:bg-rose-800',
                    ],
                };
            @endphp

            <article class="app-panel public-card border-t-4 {{ $palette['card'] }} {{ $palette['top'] }}">
                <div class="flex items-start justify-between gap-3">
                    <span class="inline-flex h-11 w-11 items-center justify-center rounded-xl border bg-white {{ $palette['badge'] }}">
                        <x-ui.icon name="heart" class="h-5 w-5" />
                    </span>
                    <span class="rounded-full border px-3 py-1 text-xs font-bold uppercase tracking-normal {{ $palette['badge'] }}">{{ $room->scripture_reference }}</span>
                </div>

                <h2 class="mt-4 text-xl font-black tracking-normal text-slate-950">{{ $room->name }}</h2>
                <p class="mt-2 flex-1 text-sm leading-6 text-slate-600">{{ $room->description }}</p>

                <div class="mt-5 grid grid-cols-3 gap-2 text-center">
                    <div class="rounded-xl border border-slate-200 bg-slate-50 px-2 py-3">
                        <p class="text-lg font-black tracking-normal text-slate-950">{{ $room->memberships_count }}</p>
                        <p class="text-xs font-bold text-slate-500">joined</p>
                    </div>
                    <div class="rounded-xl border border-amber-200 bg-amber-50 px-2 py-3">
                        <p class="text-lg font-black tracking-normal text-slate-950">{{ $room->open_requests_count }}</p>
                        <p class="text-xs font-bold text-amber-900">open</p>
                    </div>
                    <div class="rounded-xl border border-emerald-200 bg-emerald-50 px-2 py-3">
                        <p class="text-lg font-black tracking-normal text-slate-950">{{ $room->answered_requests_count }}</p>
                        <p class="text-xs font-bold text-emerald-900">answered</p>
                    </div>
                </div>

                <a href="{{ route('prayer-rooms.show', $room->slug) }}" class="mt-5 btn-primary w-full {{ $palette['button'] }}"><x-ui.icon name="users" class="h-4 w-4" /> Open room</a>
            </article>
        @endforeach
    </div>
</div>
