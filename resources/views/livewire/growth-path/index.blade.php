<div class="space-y-6 sm:space-y-8">
    @php
        $definition = $path['definition'];
        $devotional = $path['devotional'];
        $bibleBook = $path['bible_book'];
    @endphp

    <section class="app-panel overflow-hidden border-violet-200 p-0 sm:p-0">
        <div class="color-strip rounded-none">
            <span class="bg-violet-500"></span>
            <span class="bg-fuchsia-500"></span>
            <span class="bg-sky-500"></span>
            <span class="bg-emerald-500"></span>
            <span class="bg-amber-400"></span>
        </div>
        <div class="grid gap-6 p-5 sm:p-6 lg:grid-cols-[minmax(0,1fr)_minmax(18rem,28rem)] lg:items-end">
            <div>
                <p class="app-eyebrow border-violet-200 bg-violet-50 text-violet-900"><x-ui.icon name="route" class="h-4 w-4" /> Personalized path</p>
                <h1 class="mt-3 app-section-title">A daily path for your current season</h1>
                <p class="mt-2 max-w-2xl text-sm leading-6 text-slate-600">Choose what you are walking through and MannaRise will focus your devotional, scripture, affirmation, prayer, and journal prompt.</p>
            </div>

            <form wire:submit="saveSeason" class="rounded-xl border border-violet-100 bg-violet-50 p-4">
                <label class="block text-sm font-bold text-slate-700">Current season</label>
                <select wire:model="season" class="field-input mt-1 border-violet-300 focus:border-violet-600 focus:ring-violet-100">
                    @foreach ($seasons as $key => $option)
                        <option value="{{ $key }}">{{ $option['label'] }}</option>
                    @endforeach
                </select>
                @error('season') <p class="mt-1 text-sm text-red-700">{{ $message }}</p> @enderror
                <button type="submit" class="mt-3 btn-primary w-full bg-violet-700 hover:bg-violet-800"><x-ui.icon name="route" class="h-4 w-4" /> Save path</button>
            </form>
        </div>
    </section>

    <section class="grid gap-4 lg:grid-cols-3">
        <article class="app-panel border-blue-200 bg-blue-50">
            <p class="app-eyebrow border-blue-200 bg-white text-blue-900"><x-ui.icon name="book-open" class="h-4 w-4" /> Scripture focus</p>
            <h2 class="mt-4 font-display text-2xl font-black tracking-normal text-slate-950">{{ $definition['reference'] }}</h2>
            <p class="mt-2 text-sm leading-6 text-slate-600">Read this chapter slowly and let the reference shape today&apos;s prayer.</p>
            @if ($bibleBook)
                <a href="{{ route('bible', ['book' => $bibleBook->slug, 'chapter' => $definition['chapter']]) }}" class="mt-4 btn-secondary border-blue-200 text-blue-900">Open passage <x-ui.icon name="chevron-right" class="h-4 w-4" /></a>
            @endif
        </article>

        <article class="app-panel border-amber-200 bg-amber-50">
            <p class="app-eyebrow border-amber-200 bg-white text-amber-900"><x-ui.icon name="sparkles" class="h-4 w-4" /> Affirmation</p>
            <p class="mt-4 font-serif text-2xl font-semibold leading-9 text-slate-950">{{ $definition['affirmation'] }}</p>
        </article>

        <article class="app-panel border-rose-200 bg-rose-50">
            <p class="app-eyebrow border-rose-200 bg-white text-rose-900"><x-ui.icon name="heart" class="h-4 w-4" /> Prayer</p>
            <p class="mt-4 text-lg font-semibold leading-8 text-slate-950">{{ $definition['prayer'] }}</p>
        </article>
    </section>

    <section class="grid gap-6 lg:grid-cols-[minmax(0,1fr)_minmax(20rem,0.85fr)]">
        <article class="app-panel border-emerald-200">
            <div class="flex flex-col gap-3 sm:flex-row sm:items-start sm:justify-between">
                <div>
                    <p class="app-eyebrow border-emerald-200 bg-emerald-50 text-emerald-900"><x-ui.icon name="sparkles" class="h-4 w-4" /> Recommended devotional</p>
                    @if ($devotional)
                        <h2 class="mt-3 app-section-title">{{ $devotional->title }}</h2>
                        <p class="mt-2 text-sm font-bold text-emerald-900">{{ $devotional->category?->name }}</p>
                    @else
                        <h2 class="mt-3 app-section-title">No devotional match yet</h2>
                    @endif
                </div>
                @if ($devotional)
                    <a href="{{ route('devotionals.show', $devotional->slug) }}" class="btn-primary w-full sm:w-auto">Read <x-ui.icon name="chevron-right" class="h-4 w-4" /></a>
                @endif
            </div>
            @if ($devotional)
                <p class="mt-4 text-sm leading-6 text-slate-600">{{ \Illuminate\Support\Str::limit(strip_tags($devotional->content), 220) }}</p>
            @else
                <p class="mt-4 text-sm leading-6 text-slate-600">Publish more devotionals or categories for this path to become more personalized.</p>
            @endif
        </article>

        <aside class="space-y-4">
            <article class="app-panel border-sky-200 bg-sky-50">
                <p class="app-eyebrow border-sky-200 bg-white text-sky-900"><x-ui.icon name="journal" class="h-4 w-4" /> Journal prompt</p>
                <p class="mt-4 text-lg font-semibold leading-8 text-slate-950">{{ $definition['journal_prompt'] }}</p>
                <a href="{{ route('journal.index') }}" class="mt-4 btn-secondary border-sky-200">Open journal</a>
            </article>

            <article class="app-panel border-lime-200 bg-lime-50">
                <p class="app-eyebrow border-lime-200 bg-white text-lime-900"><x-ui.icon name="check-circle" class="h-4 w-4" /> Today&apos;s action</p>
                <p class="mt-4 text-lg font-semibold leading-8 text-slate-950">{{ $definition['action'] }}</p>
            </article>
        </aside>
    </section>
</div>
