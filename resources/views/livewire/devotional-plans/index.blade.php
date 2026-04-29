<div class="space-y-6 sm:space-y-8">
    <section class="app-panel overflow-hidden border-emerald-200 p-0 sm:p-0">
        <div class="color-strip rounded-none">
            <span class="bg-emerald-500"></span>
            <span class="bg-lime-500"></span>
            <span class="bg-amber-400"></span>
            <span class="bg-rose-500"></span>
            <span class="bg-sky-500"></span>
        </div>
        <div class="flex flex-col gap-4 p-5 sm:p-6 md:flex-row md:items-end md:justify-between">
            <div>
                <p class="app-eyebrow border-emerald-200 bg-emerald-50 text-emerald-900"><x-ui.icon name="calendar" class="h-4 w-4" /> Plans</p>
                <h1 class="mt-3 app-section-title">Devotional plans</h1>
                <p class="mt-2 max-w-2xl text-sm leading-6 text-slate-600">Follow structured plans for courage, prayer, and purpose with day-by-day progress tracking.</p>
            </div>
            @guest
                <a href="{{ route('login') }}" class="btn-primary w-full sm:w-auto"><x-ui.icon name="log-in" class="h-4 w-4" /> Log in to track progress</a>
            @endguest
        </div>
    </section>

    <div class="grid gap-4 lg:grid-cols-3">
        @foreach ($plans as $plan)
            @php
                $palette = match ($plan['accent']) {
                    'amber' => ['panel' => 'border-amber-200', 'soft' => 'border-amber-200 bg-amber-50 text-amber-900', 'bar' => 'bg-amber-500', 'button' => 'bg-amber-600 text-slate-950 hover:bg-amber-500'],
                    'rose' => ['panel' => 'border-rose-200', 'soft' => 'border-rose-200 bg-rose-50 text-rose-900', 'bar' => 'bg-rose-600', 'button' => 'bg-rose-700 hover:bg-rose-800'],
                    default => ['panel' => 'border-emerald-200', 'soft' => 'border-emerald-200 bg-emerald-50 text-emerald-900', 'bar' => 'bg-emerald-700', 'button' => 'bg-emerald-700 hover:bg-emerald-800'],
                };
            @endphp

            <article class="app-panel public-card border-t-4 {{ $palette['panel'] }} {{ $plan['accent'] === 'amber' ? 'border-t-amber-400' : ($plan['accent'] === 'rose' ? 'border-t-rose-500' : 'border-t-emerald-500') }}">
                <div class="flex flex-wrap items-center justify-between gap-3">
                    <span class="rounded-full border px-3 py-1 text-xs font-bold uppercase tracking-normal {{ $palette['soft'] }}">{{ $plan['duration'] }} days</span>
                    <span class="rounded-full bg-slate-100 px-3 py-1 text-xs font-bold uppercase tracking-normal text-slate-700">{{ $plan['completed_days'] }}/{{ $plan['duration'] }}</span>
                </div>
                <h2 class="mt-4 text-2xl font-black tracking-normal text-slate-950">{{ $plan['title'] }}</h2>
                <p class="mt-3 flex-1 text-sm leading-6 text-slate-600">{{ $plan['description'] }}</p>
                <div class="mt-5 h-3 overflow-hidden rounded-full bg-slate-100">
                    <div class="h-full rounded-full {{ $palette['bar'] }}" style="width: {{ min(100, $plan['progress_percent']) }}%"></div>
                </div>
                <a href="{{ route('devotional-plans.show', $plan['slug']) }}" class="mt-5 btn-primary w-full {{ $palette['button'] }}">
                    Open plan <x-ui.icon name="chevron-right" class="h-4 w-4" />
                </a>
            </article>
        @endforeach
    </div>
</div>
