<div class="grid gap-6 lg:grid-cols-[minmax(0,1fr)_22rem]">
    <article class="rounded-lg border border-stone-200 bg-white p-6 shadow-sm sm:p-8">
        <div class="flex flex-wrap items-center gap-3 text-sm text-stone-600">
            <span class="rounded-md bg-emerald-50 px-2.5 py-1 font-semibold text-emerald-800">{{ $devotional->category?->name }}</span>
            <span>{{ $devotional->reading_time }} min read</span>
            @if ($devotional->published_at)
                <span>{{ $devotional->published_at->format('M j, Y') }}</span>
            @endif
        </div>

        <h1 class="mt-5 text-3xl font-semibold text-stone-950 sm:text-4xl">{{ $devotional->title }}</h1>

        @if ($devotional->bible_reference || $devotional->bible_text)
            <div class="mt-6 rounded-lg border border-stone-200 bg-stone-50 p-5">
                @if ($devotional->bible_reference)
                    <p class="text-sm font-semibold text-stone-800">{{ $devotional->bible_reference }}</p>
                @endif
                @if ($devotional->bible_text)
                    <p class="mt-3 text-base leading-7 text-stone-700">{!! nl2br(e($devotional->bible_text)) !!}</p>
                @endif
            </div>
        @endif

        <div class="mt-8 max-w-none leading-8 text-stone-800">
            {!! nl2br(e($devotional->content)) !!}
        </div>

        <div class="mt-8 grid gap-4 md:grid-cols-3">
            @if ($devotional->reflection_question)
                <section class="rounded-lg border border-stone-200 bg-white p-4">
                    <h2 class="text-sm font-semibold text-stone-950">Reflection</h2>
                    <p class="mt-2 text-sm leading-6 text-stone-600">{{ $devotional->reflection_question }}</p>
                </section>
            @endif

            @if ($devotional->prayer_point)
                <section class="rounded-lg border border-stone-200 bg-white p-4">
                    <h2 class="text-sm font-semibold text-stone-950">Prayer</h2>
                    <p class="mt-2 text-sm leading-6 text-stone-600">{{ $devotional->prayer_point }}</p>
                </section>
            @endif

            @if ($devotional->declaration)
                <section class="rounded-lg border border-stone-200 bg-white p-4">
                    <h2 class="text-sm font-semibold text-stone-950">Declaration</h2>
                    <p class="mt-2 text-sm leading-6 text-stone-600">{{ $devotional->declaration }}</p>
                </section>
            @endif
        </div>
    </article>

    <aside class="space-y-4">
        <div class="rounded-lg border border-stone-200 bg-white p-5 shadow-sm">
            <h2 class="font-semibold text-stone-950">Reading actions</h2>
            <div class="mt-4 space-y-3">
                <button type="button" wire:click="toggleFavorite" class="w-full rounded-md border border-stone-300 px-4 py-2 text-sm font-semibold text-stone-800 hover:bg-stone-100">
                    {{ $isFavorited ? 'Remove favorite' : 'Save favorite' }}
                </button>
                <button type="button" wire:click="markCompleted" class="w-full rounded-md bg-emerald-700 px-4 py-2 text-sm font-semibold text-white hover:bg-emerald-800">
                    {{ $completedToday ? 'Completed today' : 'Mark completed' }}
                </button>
            </div>
        </div>

        <div class="rounded-lg border border-stone-200 bg-white p-5 shadow-sm">
            <h2 class="font-semibold text-stone-950">Journal reflection</h2>
            @auth
                <form wire:submit="saveJournalEntry" class="mt-4 space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-stone-700">Title</label>
                        <input type="text" wire:model="journalTitle" class="mt-1 w-full rounded-md border border-stone-300 px-3 py-2 text-sm outline-none focus:border-emerald-600 focus:ring-2 focus:ring-emerald-100">
                        @error('journalTitle') <p class="mt-1 text-sm text-red-700">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-stone-700">Reflection</label>
                        <textarea wire:model="journalContent" rows="6" class="mt-1 w-full rounded-md border border-stone-300 px-3 py-2 text-sm outline-none focus:border-emerald-600 focus:ring-2 focus:ring-emerald-100"></textarea>
                        @error('journalContent') <p class="mt-1 text-sm text-red-700">{{ $message }}</p> @enderror
                    </div>
                    <button type="submit" class="w-full rounded-md bg-stone-900 px-4 py-2 text-sm font-semibold text-white hover:bg-stone-800">Save journal entry</button>
                </form>
            @else
                <p class="mt-3 text-sm leading-6 text-stone-600">Log in to save favorites, track completions, and journal your reflection.</p>
                <a href="{{ route('login') }}" class="mt-4 inline-flex rounded-md bg-emerald-700 px-4 py-2 text-sm font-semibold text-white hover:bg-emerald-800">Log in</a>
            @endauth
        </div>
    </aside>
</div>
