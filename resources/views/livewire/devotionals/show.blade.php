<div class="grid gap-5 lg:grid-cols-[minmax(0,1fr)_minmax(18rem,22rem)] lg:items-start">
    <article class="space-y-5">
        <section class="app-panel overflow-hidden border-amber-200 p-0 sm:p-0">
            <div class="color-strip rounded-none">
                <span class="bg-amber-400"></span>
                <span class="bg-yellow-400"></span>
                <span class="bg-lime-500"></span>
                <span class="bg-emerald-500"></span>
                <span class="bg-teal-500"></span>
                <span class="bg-sky-500"></span>
                <span class="bg-violet-500"></span>
            </div>
            <div class="p-5 sm:p-8">
                <div class="flex flex-wrap items-center gap-3 text-sm text-slate-600">
                    <span class="inline-flex items-center gap-2 rounded-full bg-emerald-50 px-3 py-1 font-bold text-emerald-900">🌿 {{ $devotional->category?->name }}</span>
                    <span class="rounded-full bg-sky-50 px-3 py-1 font-bold text-sky-900">{{ $devotional->reading_time }} min read</span>
            @if ($devotional->published_at)
                    <span class="rounded-full bg-mist-100 px-3 py-1 font-bold text-mist-800">{{ $devotional->published_at->format('M j, Y') }}</span>
            @endif
                </div>

                <h1 class="mt-5 text-3xl font-black tracking-normal text-slate-950 sm:text-4xl">{{ $devotional->title }}</h1>

                @if ($devotional->bible_reference || $devotional->bible_text)
                    <blockquote class="mt-6 border-l-4 border-amber-400 bg-amber-50 px-4 py-4">
                        @if ($devotional->bible_reference)
                            <p class="flex items-center gap-2 text-sm font-black text-amber-900">📖 {{ $devotional->bible_reference }}</p>
                        @endif
                        @if ($devotional->bible_text)
                            <p class="mt-3 text-base leading-7 text-slate-800">{!! nl2br(e($devotional->bible_text)) !!}</p>
                        @endif
                    </blockquote>
                @endif

                <div class="mt-8 max-w-none text-base leading-8 text-slate-800 sm:text-lg">
                    {!! nl2br(e($devotional->content)) !!}
                </div>
            </div>
        </section>

        <div class="grid gap-4 md:grid-cols-3">
            @if ($devotional->reflection_question)
                <section class="app-panel border-sky-200 bg-sky-50">
                    <h2 class="flex items-center gap-2 text-sm font-black tracking-normal text-sky-950"><x-ui.icon name="journal" class="h-4 w-4" /> Reflection 📝</h2>
                    <p class="mt-2 text-sm leading-6 text-slate-700">{{ $devotional->reflection_question }}</p>
                </section>
            @endif

            @if ($devotional->prayer_point)
                <section class="app-panel border-rose-200 bg-rose-50">
                    <h2 class="flex items-center gap-2 text-sm font-black tracking-normal text-rose-950"><x-ui.icon name="heart" class="h-4 w-4" /> Prayer 🙏</h2>
                    <p class="mt-2 text-sm leading-6 text-slate-700">{{ $devotional->prayer_point }}</p>
                </section>
            @endif

            @if ($devotional->declaration)
                <section class="app-panel border-violet-200 bg-violet-50">
                    <h2 class="flex items-center gap-2 text-sm font-black tracking-normal text-violet-950"><x-ui.icon name="sparkles" class="h-4 w-4" /> Declaration ✨</h2>
                    <p class="mt-2 text-sm leading-6 text-slate-700">{{ $devotional->declaration }}</p>
                </section>
            @endif
        </div>
    </article>

    <aside class="space-y-4 lg:sticky lg:top-36">
        <div class="app-panel border-emerald-200 bg-emerald-50">
            <h2 class="flex items-center gap-2 font-black tracking-normal text-slate-950"><x-ui.icon name="star" class="h-4 w-4 text-emerald-800" /> Reading actions 🌟</h2>
            <div class="mt-4 space-y-3">
                <button type="button" wire:click="toggleFavorite" class="btn-secondary w-full border-emerald-200">
                    <x-ui.icon name="bookmark" class="h-4 w-4" /> {{ $isFavorited ? 'Remove favorite' : 'Save favorite' }}
                </button>
                <button type="button" wire:click="markCompleted" class="btn-primary w-full">
                    <x-ui.icon name="sparkles" class="h-4 w-4" /> {{ $completedToday ? 'Completed today' : 'Mark completed' }}
                </button>
            </div>
        </div>

        <div class="app-panel border-mauve-200 bg-mauve-50">
            <h2 class="flex items-center gap-2 font-black tracking-normal text-slate-950"><x-ui.icon name="journal" class="h-4 w-4 text-mauve-800" /> Journal reflection 📝</h2>
            @auth
                <form wire:submit="saveJournalEntry" class="mt-4 space-y-4">
                    <div>
                        <label class="block text-sm font-bold text-slate-700">Title</label>
                        <input type="text" wire:model="journalTitle" class="field-input mt-1 border-mauve-300 focus:border-mauve-600 focus:ring-mauve-100">
                        @error('journalTitle') <p class="mt-1 text-sm text-red-700">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-bold text-slate-700">Reflection</label>
                        <textarea wire:model="journalContent" rows="6" class="field-input mt-1 border-mauve-300 focus:border-mauve-600 focus:ring-mauve-100"></textarea>
                        @error('journalContent') <p class="mt-1 text-sm text-red-700">{{ $message }}</p> @enderror
                    </div>
                    <button type="submit" class="btn-primary w-full bg-mauve-700 hover:bg-mauve-800">Save journal entry</button>
                </form>
            @else
                <p class="mt-3 text-sm leading-6 text-slate-700">Log in to save favorites, track completions, and journal your reflection.</p>
                <a href="{{ route('login') }}" class="mt-4 btn-primary"><x-ui.icon name="log-in" class="h-4 w-4" /> Log in</a>
            @endauth
        </div>
    </aside>
</div>
