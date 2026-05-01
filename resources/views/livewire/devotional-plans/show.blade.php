@php
    $palette = match ($planDefinition['accent']) {
        'amber' => [
            'panel' => 'border-amber-200',
            'soft' => 'border-amber-200 bg-amber-50 text-amber-900',
            'button' => 'bg-amber-600 text-slate-950 hover:bg-amber-500',
            'bar' => 'bg-amber-500',
        ],
        'rose' => [
            'panel' => 'border-rose-200',
            'soft' => 'border-rose-200 bg-rose-50 text-rose-900',
            'button' => 'bg-rose-700 hover:bg-rose-800',
            'bar' => 'bg-rose-600',
        ],
        default => [
            'panel' => 'border-emerald-200',
            'soft' => 'border-emerald-200 bg-emerald-50 text-emerald-900',
            'button' => 'bg-emerald-700 hover:bg-emerald-800',
            'bar' => 'bg-emerald-700',
        ],
    };
@endphp

<div class="space-y-6 sm:space-y-8">
    @if (session('status'))
        <div class="app-panel border-emerald-200 bg-emerald-50 text-sm font-bold text-emerald-900">
            {{ session('status') }}
        </div>
    @endif

    <section class="app-panel overflow-hidden p-0 sm:p-0 {{ $palette['panel'] }}">
        <div class="color-strip rounded-none">
            <span class="bg-emerald-500"></span>
            <span class="bg-amber-400"></span>
            <span class="bg-rose-500"></span>
            <span class="bg-sky-500"></span>
            <span class="bg-violet-500"></span>
        </div>
        <div class="grid gap-5 p-5 sm:p-6 lg:grid-cols-[minmax(0,1fr)_minmax(16rem,26rem)] lg:items-end">
            <div>
                <a href="{{ route('devotional-plans.index') }}" class="mb-4 inline-flex items-center gap-2 text-sm font-bold text-slate-600 hover:text-slate-950"><x-ui.icon name="chevron-left" class="h-4 w-4" /> Plans</a>
                <p class="app-eyebrow {{ $palette['soft'] }}"><x-ui.icon name="calendar" class="h-4 w-4" /> {{ $planDefinition['duration'] }} day plan</p>
                <h1 class="mt-3 app-section-title">{{ $planDefinition['title'] }}</h1>
                <p class="mt-2 max-w-2xl text-sm leading-6 text-slate-600">{{ $planDefinition['description'] }}</p>
            </div>

            <div class="app-surface p-4">
                <div class="flex items-center justify-between gap-3">
                    <span class="text-sm font-bold text-slate-600">Progress</span>
                    <span class="text-sm font-black text-slate-950">{{ $completedCount }}/{{ $planDefinition['duration'] }} days</span>
                </div>
                <div class="mt-3 h-3 overflow-hidden rounded-full bg-slate-100">
                    <div class="h-full rounded-full {{ $palette['bar'] }}" style="width: {{ min(100, $progressPercent) }}%"></div>
                </div>
                @guest
                    <a href="{{ route('login') }}" class="mt-4 btn-primary w-full {{ $palette['button'] }}"><x-ui.icon name="log-in" class="h-4 w-4" /> Log in to track</a>
                @endguest
            </div>
        </div>
    </section>

    <div class="space-y-4">
        @foreach ($days as $day)
            @php
                $completion = $completions[$day['day_number']] ?? null;
                $devotional = $day['devotional'];
            @endphp

            <article class="app-panel border-l-4 {{ $completion ? 'border-l-emerald-500' : 'border-l-slate-200' }}">
                <div class="grid gap-4 lg:grid-cols-[minmax(0,1fr)_auto] lg:items-start">
                    <div>
                        <div class="flex flex-wrap items-center gap-2">
                            <span class="rounded-full px-3 py-1 text-xs font-bold uppercase tracking-normal {{ $completion ? 'bg-emerald-50 text-emerald-900' : $palette['soft'] }}">Day {{ $day['day_number'] }}</span>
                            <span class="rounded-full bg-sky-50 px-3 py-1 text-xs font-bold uppercase tracking-normal text-sky-900">{{ $day['focus'] }}</span>
                            @if ($completion)
                                <span class="rounded-full bg-emerald-50 px-3 py-1 text-xs font-bold uppercase tracking-normal text-emerald-900">Completed {{ $completion->completed_on->format('M j') }}</span>
                            @endif
                        </div>

                        <h2 class="mt-3 text-xl font-black tracking-normal text-slate-950">{{ $day['title'] }}</h2>
                        <p class="mt-2 inline-flex items-center gap-2 text-sm font-bold text-olive-800"><x-ui.icon name="book-open" class="h-4 w-4" /> {{ $day['reference'] }}</p>
                        <p class="mt-3 text-sm leading-6 text-slate-600">{{ $day['summary'] }}</p>
                    </div>

                    <div class="flex flex-col gap-2 sm:flex-row lg:w-56 lg:flex-col">
                        @if ($devotional)
                            <a href="{{ route('devotionals.show', $devotional->slug) }}" class="btn-secondary w-full px-3">Read <x-ui.icon name="chevron-right" class="h-4 w-4" /></a>
                        @endif
                        @auth
                            <button type="button" wire:click="completeDay({{ $day['day_number'] }}, {{ $devotional?->id ?? 'null' }})" class="btn-primary w-full px-3 {{ $palette['button'] }}">
                                <x-ui.icon name="check-circle" class="h-4 w-4" /> {{ $completion ? 'Completed' : 'Mark complete' }}
                            </button>
                        @else
                            <a href="{{ route('login') }}" class="btn-primary w-full px-3 {{ $palette['button'] }}"><x-ui.icon name="log-in" class="h-4 w-4" /> Track</a>
                        @endauth
                    </div>
                </div>
            </article>
        @endforeach
    </div>
</div>
