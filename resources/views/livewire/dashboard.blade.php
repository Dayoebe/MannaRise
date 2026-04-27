<div class="space-y-6">
    <div class="flex flex-col gap-4 md:flex-row md:items-end md:justify-between">
        <div>
            <h1 class="text-3xl font-semibold text-stone-950">Dashboard</h1>
            <p class="mt-2 text-sm text-stone-600">Welcome back, {{ auth()->user()->name }}.</p>
        </div>
        @if ($todayDevotional)
            <a href="{{ route('devotionals.show', $todayDevotional->slug) }}" class="rounded-md bg-emerald-700 px-4 py-2.5 text-sm font-semibold text-white hover:bg-emerald-800">Continue reading</a>
        @endif
    </div>

    <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-5">
        @foreach ([['Favorites', $stats['favorites']], ['Journal', $stats['journal_entries']], ['Prayers', $stats['prayer_requests']], ['Completed', $stats['completed']], ['Streak', $stats['streak'].' days']] as [$label, $value])
            <div class="rounded-lg border border-stone-200 bg-white p-5 shadow-sm">
                <p class="text-sm text-stone-600">{{ $label }}</p>
                <p class="mt-2 text-3xl font-semibold text-stone-950">{{ $value }}</p>
            </div>
        @endforeach
    </div>

    <div class="grid gap-6 lg:grid-cols-[1.1fr_0.9fr]">
        <section class="rounded-lg border border-stone-200 bg-white p-6 shadow-sm">
            <h2 class="text-xl font-semibold text-stone-950">Recent journal entries</h2>
            <div class="mt-4 space-y-3">
                @forelse ($recentJournalEntries as $entry)
                    <article class="border-b border-stone-100 pb-3 last:border-b-0 last:pb-0">
                        <h3 class="font-semibold text-stone-950">{{ $entry->title }}</h3>
                        <p class="mt-1 text-sm text-stone-500">{{ $entry->entry_date->format('M j, Y') }} @if ($entry->devotional) · {{ $entry->devotional->title }} @endif</p>
                        <p class="mt-2 text-sm leading-6 text-stone-600">{{ \Illuminate\Support\Str::limit($entry->content, 130) }}</p>
                    </article>
                @empty
                    <p class="text-sm text-stone-600">Your reflections will appear here.</p>
                @endforelse
            </div>
            <a href="{{ route('journal.index') }}" class="mt-5 inline-flex text-sm font-semibold text-emerald-800 hover:underline">Open journal</a>
        </section>

        <section class="rounded-lg border border-stone-200 bg-white p-6 shadow-sm">
            <h2 class="text-xl font-semibold text-stone-950">Saved devotionals</h2>
            <div class="mt-4 space-y-3">
                @forelse ($recentFavorites as $devotional)
                    <a href="{{ route('devotionals.show', $devotional->slug) }}" class="block rounded-md border border-stone-200 p-3 hover:border-emerald-300">
                        <span class="block font-semibold text-stone-950">{{ $devotional->title }}</span>
                        <span class="mt-1 block text-sm text-stone-500">{{ $devotional->category?->name }}</span>
                    </a>
                @empty
                    <p class="text-sm text-stone-600">Saved devotionals will appear here.</p>
                @endforelse
            </div>
            <a href="{{ route('favorites.index') }}" class="mt-5 inline-flex text-sm font-semibold text-emerald-800 hover:underline">View favorites</a>
        </section>
    </div>
</div>
