@php
    $palette = match ($room->accent) {
        'emerald' => [
            'panel' => 'border-emerald-200',
            'soft' => 'border-emerald-200 bg-emerald-50 text-emerald-900',
            'button' => 'bg-emerald-700 hover:bg-emerald-800',
            'focus' => 'border-emerald-300 focus:border-emerald-600 focus:ring-emerald-100',
        ],
        'amber' => [
            'panel' => 'border-amber-200',
            'soft' => 'border-amber-200 bg-amber-50 text-amber-900',
            'button' => 'bg-amber-600 text-slate-950 hover:bg-amber-500',
            'focus' => 'border-amber-300 focus:border-amber-600 focus:ring-amber-100',
        ],
        'sky' => [
            'panel' => 'border-sky-200',
            'soft' => 'border-sky-200 bg-sky-50 text-sky-900',
            'button' => 'bg-sky-700 hover:bg-sky-800',
            'focus' => 'border-sky-300 focus:border-sky-600 focus:ring-sky-100',
        ],
        'fuchsia' => [
            'panel' => 'border-fuchsia-200',
            'soft' => 'border-fuchsia-200 bg-fuchsia-50 text-fuchsia-900',
            'button' => 'bg-fuchsia-700 hover:bg-fuchsia-800',
            'focus' => 'border-fuchsia-300 focus:border-fuchsia-600 focus:ring-fuchsia-100',
        ],
        'indigo' => [
            'panel' => 'border-indigo-200',
            'soft' => 'border-indigo-200 bg-indigo-50 text-indigo-900',
            'button' => 'bg-indigo-700 hover:bg-indigo-800',
            'focus' => 'border-indigo-300 focus:border-indigo-600 focus:ring-indigo-100',
        ],
        default => [
            'panel' => 'border-rose-200',
            'soft' => 'border-rose-200 bg-rose-50 text-rose-900',
            'button' => 'bg-rose-700 hover:bg-rose-800',
            'focus' => 'border-rose-300 focus:border-rose-600 focus:ring-rose-100',
        ],
    };
@endphp

<div class="space-y-6 sm:space-y-8">
    <section class="app-panel overflow-hidden p-0 sm:p-0 {{ $palette['panel'] }}">
        <div class="color-strip rounded-none">
            <span class="bg-rose-500"></span>
            <span class="bg-emerald-500"></span>
            <span class="bg-amber-400"></span>
            <span class="bg-sky-500"></span>
            <span class="bg-fuchsia-500"></span>
            <span class="bg-indigo-500"></span>
        </div>
        <div class="grid gap-5 p-5 sm:p-6 lg:grid-cols-[minmax(0,1fr)_minmax(18rem,28rem)] lg:items-end">
            <div>
                <a href="{{ route('prayer-rooms.index') }}" class="mb-4 inline-flex items-center gap-2 text-sm font-bold text-slate-600 hover:text-slate-950"><x-ui.icon name="chevron-left" class="h-4 w-4" /> Prayer rooms</a>
                <p class="app-eyebrow {{ $palette['soft'] }}"><x-ui.icon name="heart" class="h-4 w-4" /> {{ $room->scripture_reference }}</p>
                <h1 class="mt-3 app-section-title">{{ $room->name }} prayer room</h1>
                <p class="mt-2 max-w-2xl text-sm leading-6 text-slate-600">{{ $room->description }}</p>
            </div>

            <div class="grid gap-3">
                @auth
                    @if ($membership)
                        <div class="app-surface grid gap-3 p-4 sm:grid-cols-3">
                            <div>
                                <p class="text-xs font-bold uppercase tracking-normal text-slate-500">Streak</p>
                                <p class="mt-1 text-2xl font-black tracking-normal text-slate-950">{{ $membership->current_streak }}</p>
                            </div>
                            <div>
                                <p class="text-xs font-bold uppercase tracking-normal text-slate-500">Best</p>
                                <p class="mt-1 text-2xl font-black tracking-normal text-slate-950">{{ $membership->longest_streak }}</p>
                            </div>
                            <div>
                                <p class="text-xs font-bold uppercase tracking-normal text-slate-500">Total</p>
                                <p class="mt-1 text-2xl font-black tracking-normal text-slate-950">{{ $membership->total_prayers }}</p>
                            </div>
                        </div>
                        <button type="button" wire:click="leave" wire:confirm="Leave this prayer room?" class="btn-secondary w-full"><x-ui.icon name="log-out" class="h-4 w-4" /> Leave room</button>
                    @else
                        <button type="button" wire:click="join" class="btn-primary w-full {{ $palette['button'] }}"><x-ui.icon name="users" class="h-4 w-4" /> Join room</button>
                    @endif
                @else
                    <a href="{{ route('login') }}" class="btn-primary w-full {{ $palette['button'] }}"><x-ui.icon name="log-in" class="h-4 w-4" /> Log in to join</a>
                @endauth

                <a href="{{ route('prayer-requests.submit', ['room' => $room->slug]) }}" class="btn-secondary w-full"><x-ui.icon name="send" class="h-4 w-4" /> Submit request here</a>
            </div>
        </div>
    </section>

    <div class="grid gap-3 sm:grid-cols-3">
        <div class="metric-card {{ $palette['soft'] }}">
            <p class="flex items-center gap-2 text-sm font-bold"><x-ui.icon name="heart" class="h-4 w-4" /> Prayed today</p>
            <p class="mt-2 text-3xl font-black tracking-normal text-slate-950">{{ $todayPrayerCount }}</p>
        </div>
        <div class="metric-card border-amber-200 bg-amber-50 text-amber-900">
            <p class="flex items-center gap-2 text-sm font-bold"><x-ui.icon name="star" class="h-4 w-4" /> Open requests</p>
            <p class="mt-2 text-3xl font-black tracking-normal text-slate-950">{{ $openCount }}</p>
        </div>
        <div class="metric-card border-emerald-200 bg-emerald-50 text-emerald-900">
            <p class="flex items-center gap-2 text-sm font-bold"><x-ui.icon name="check-circle" class="h-4 w-4" /> Answered</p>
            <p class="mt-2 text-3xl font-black tracking-normal text-slate-950">{{ $answeredCount }}</p>
        </div>
    </div>

    <div class="grid gap-3 md:grid-cols-[1fr_minmax(12rem,auto)_auto]">
        <label class="block">
            <span class="mb-2 flex items-center gap-2 text-sm font-bold text-slate-700"><x-ui.icon name="search" class="h-4 w-4" /> Search</span>
            <input type="search" wire:model.live.debounce.300ms="search" placeholder="Search this room" class="field-input {{ $palette['focus'] }}">
        </label>
        <label class="block">
            <span class="mb-2 flex items-center gap-2 text-sm font-bold text-slate-700"><x-ui.icon name="star" class="h-4 w-4" /> Status</span>
            <select wire:model.live="status" class="field-input {{ $palette['focus'] }}">
                <option value="open">Open requests</option>
                <option value="answered">Answered prayers</option>
                <option value="all">All requests</option>
            </select>
        </label>
        <div class="app-surface flex min-h-11 items-center rounded-xl px-4 py-2 text-sm font-bold text-slate-700 md:self-end">
            {{ $openCount }} open &middot; {{ $answeredCount }} answered
        </div>
    </div>

    <div class="grid gap-4 lg:grid-cols-2">
        @forelse ($requests as $request)
            @php
                $canEdit = auth()->check() && ($request->user_id === auth()->id() || auth()->user()->hasAdminAccess());
            @endphp

            <article wire:key="room-request-{{ $request->id }}" class="app-panel public-card border-t-4 {{ $request->is_answered ? 'border-t-emerald-500' : 'border-t-amber-400' }}">
                <div class="flex flex-wrap items-center justify-between gap-3">
                    <span class="rounded-full px-3 py-1 text-xs font-bold uppercase tracking-normal {{ $request->is_answered ? 'bg-emerald-50 text-emerald-900' : 'bg-amber-50 text-amber-900' }}">
                        {{ $request->is_answered ? 'Answered' : 'Open' }}
                    </span>
                    <span class="text-xs font-bold text-slate-500">{{ $request->created_at->diffForHumans() }}</span>
                </div>

                <h2 class="mt-3 text-lg font-black tracking-normal text-slate-950">{{ $request->title }}</h2>
                <p class="mt-2 text-sm font-bold text-slate-500">{{ $request->name ?: 'Anonymous' }}</p>
                <p class="mt-3 flex-1 text-sm leading-6 text-slate-700">{{ $request->body }}</p>

                @if ($request->updates->isNotEmpty())
                    <div class="mt-4 space-y-2">
                        @foreach ($request->updates as $update)
                            <div class="rounded-xl border border-emerald-200 bg-emerald-50 px-3 py-3 text-sm leading-6 text-emerald-950">
                                <p class="font-bold">Answered update &middot; {{ $update->created_at->diffForHumans() }}</p>
                                <p class="mt-1">{{ $update->body }}</p>
                            </div>
                        @endforeach
                    </div>
                @endif

                @if ($answeringRequestId === $request->id)
                    <form wire:submit="addAnsweredUpdate({{ $request->id }})" class="mt-4 rounded-xl border border-emerald-200 bg-emerald-50 p-3">
                        <label class="block text-sm font-bold text-emerald-950">Answered-prayer update</label>
                        <textarea wire:model="answeredUpdateBody" rows="4" class="field-input mt-2 border-emerald-300 focus:border-emerald-600 focus:ring-emerald-100"></textarea>
                        @error('answeredUpdateBody') <p class="mt-1 text-sm text-red-700">{{ $message }}</p> @enderror
                        <div class="mt-3 flex flex-wrap gap-2">
                            <button type="submit" class="btn-primary bg-emerald-700 hover:bg-emerald-800"><x-ui.icon name="check-circle" class="h-4 w-4" /> Share update</button>
                            <button type="button" wire:click="cancelAnsweredUpdate" class="btn-secondary px-3">Cancel</button>
                        </div>
                    </form>
                @endif

                <div class="mt-5 flex flex-wrap items-center justify-between gap-3">
                    <span class="text-sm font-bold text-rose-800">{{ $request->prayed_count }} prayed</span>
                    <div class="flex flex-wrap gap-2">
                        @if ($canEdit && ! $request->is_answered && $answeringRequestId !== $request->id)
                            <button type="button" wire:click="beginAnsweredUpdate({{ $request->id }})" class="btn-secondary border-emerald-200 px-3 hover:bg-emerald-50"><x-ui.icon name="check-circle" class="h-4 w-4" /> Answered</button>
                        @endif
                        <button type="button" wire:click="pray({{ $request->id }})" class="btn-primary px-3 {{ $palette['button'] }}"><x-ui.icon name="heart" class="h-4 w-4" /> I prayed</button>
                    </div>
                </div>
            </article>
        @empty
            <p class="app-panel border-dashed border-slate-300 text-sm text-slate-600 lg:col-span-2">No public prayer requests match this room yet.</p>
        @endforelse
    </div>

    {{ $requests->links() }}
</div>
