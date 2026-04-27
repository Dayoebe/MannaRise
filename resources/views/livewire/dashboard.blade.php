<div class="space-y-6 sm:space-y-8">
    <div class="app-panel overflow-hidden border-emerald-200 p-0 sm:p-0">
        <div class="color-strip rounded-none">
            <span class="bg-emerald-500"></span>
            <span class="bg-teal-500"></span>
            <span class="bg-cyan-500"></span>
            <span class="bg-sky-500"></span>
            <span class="bg-blue-500"></span>
            <span class="bg-violet-500"></span>
            <span class="bg-amber-400"></span>
        </div>
        <div class="flex flex-col gap-4 p-5 sm:p-6 md:flex-row md:items-end md:justify-between">
            <div>
                <p class="app-eyebrow"><x-ui.icon name="layout-dashboard" class="h-4 w-4" /> 🏠 Dashboard</p>
                <h1 class="mt-3 app-section-title">Welcome back, {{ auth()->user()->name }}</h1>
                <p class="mt-2 text-sm text-slate-600">Your readings, journal, prayer, and growth rhythm in one place.</p>
            </div>
            @if ($todayDevotional)
                <a href="{{ route('devotionals.show', $todayDevotional->slug) }}" class="btn-primary w-full sm:w-auto"><x-ui.icon name="sparkles" class="h-4 w-4" /> Continue reading ✨</a>
            @endif
        </div>
    </div>

    <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-5">
        @foreach ([['💚', 'Favorites', $stats['favorites'], 'border-emerald-200 bg-emerald-50 text-emerald-900'], ['📝', 'Journal', $stats['journal_entries'], 'border-sky-200 bg-sky-50 text-sky-900'], ['🙏', 'Prayers', $stats['prayer_requests'], 'border-rose-200 bg-rose-50 text-rose-900'], ['✅', 'Completed', $stats['completed'], 'border-lime-200 bg-lime-50 text-lime-900'], ['🔥', 'Streak', $stats['streak'].' days', 'border-orange-200 bg-orange-50 text-orange-900']] as [$emoji, $label, $value, $classes])
            <div class="metric-card {{ $classes }}">
                <p class="flex items-center gap-2 text-sm font-bold">{{ $emoji }} {{ $label }}</p>
                <p class="mt-2 text-3xl font-black tracking-normal text-slate-950">{{ $value }}</p>
            </div>
        @endforeach
    </div>

    <div class="grid gap-6 lg:grid-cols-[1.1fr_0.9fr]">
        <section class="app-panel border-sky-200">
            <h2 class="flex items-center gap-2 text-xl font-black tracking-normal text-slate-950"><x-ui.icon name="journal" class="h-5 w-5 text-sky-800" /> Recent journal entries 📝</h2>
            <div class="mt-4 space-y-3">
                @forelse ($recentJournalEntries as $entry)
                    <article class="rounded-xl border border-sky-100 bg-sky-50 p-4">
                        <h3 class="font-black tracking-normal text-slate-950">{{ $entry->title }}</h3>
                        <p class="mt-1 text-sm font-bold text-slate-500">{{ $entry->entry_date->format('M j, Y') }} @if ($entry->devotional) · {{ $entry->devotional->title }} @endif</p>
                        <p class="mt-2 text-sm leading-6 text-slate-600">{{ \Illuminate\Support\Str::limit($entry->content, 135) }}</p>
                    </article>
                @empty
                    <p class="rounded-xl border border-dashed border-sky-200 bg-sky-50 p-4 text-sm text-slate-600">Your reflections will appear here.</p>
                @endforelse
            </div>
            <a href="{{ route('journal.index') }}" class="mt-5 btn-secondary border-sky-200">Open journal <x-ui.icon name="chevron-right" class="h-4 w-4" /></a>
        </section>

        <section class="app-panel border-emerald-200">
            <h2 class="flex items-center gap-2 text-xl font-black tracking-normal text-slate-950"><x-ui.icon name="bookmark" class="h-5 w-5 text-emerald-800" /> Saved devotionals 💚</h2>
            <div class="mt-4 space-y-3">
                @forelse ($recentFavorites as $devotional)
                    <a href="{{ route('devotionals.show', $devotional->slug) }}" class="block rounded-xl border border-emerald-100 bg-emerald-50 p-4 hover:border-emerald-300">
                        <span class="block font-black tracking-normal text-slate-950">{{ $devotional->title }}</span>
                        <span class="mt-1 block text-sm font-bold text-emerald-800">{{ $devotional->category?->name }}</span>
                    </a>
                @empty
                    <p class="rounded-xl border border-dashed border-emerald-200 bg-emerald-50 p-4 text-sm text-slate-600">Saved devotionals will appear here.</p>
                @endforelse
            </div>
            <a href="{{ route('favorites.index') }}" class="mt-5 btn-secondary border-emerald-200">View favorites <x-ui.icon name="chevron-right" class="h-4 w-4" /></a>
        </section>
    </div>
</div>
