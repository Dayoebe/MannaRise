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
                <div class="mt-3">
                    <label class="block text-sm font-bold text-slate-700">Path goal</label>
                    <input type="text" wire:model="path_goal" placeholder="What are you asking God to grow?" class="field-input mt-1 border-violet-300 focus:border-violet-600 focus:ring-violet-100">
                    @error('path_goal') <p class="mt-1 text-sm text-red-700">{{ $message }}</p> @enderror
                </div>
                <div class="mt-3">
                    <label class="block text-sm font-bold text-slate-700">Preferred rhythm</label>
                    <select wire:model="preferred_time" class="field-input mt-1 border-violet-300 focus:border-violet-600 focus:ring-violet-100">
                        <option value="">No set time</option>
                        <option value="morning">Morning</option>
                        <option value="midday">Midday</option>
                        <option value="evening">Evening</option>
                    </select>
                    @error('preferred_time') <p class="mt-1 text-sm text-red-700">{{ $message }}</p> @enderror
                </div>
                <div class="mt-3">
                    <label class="block text-sm font-bold text-slate-700">Support note</label>
                    <textarea wire:model="support_note" rows="3" placeholder="A short private note about this season" class="field-input mt-1 border-violet-300 focus:border-violet-600 focus:ring-violet-100"></textarea>
                    @error('support_note') <p class="mt-1 text-sm text-red-700">{{ $message }}</p> @enderror
                </div>
                <button type="submit" class="mt-3 btn-primary w-full bg-violet-700 hover:bg-violet-800"><x-ui.icon name="route" class="h-4 w-4" /> Save path</button>
            </form>
        </div>
    </section>

    <section class="app-panel border-indigo-200 bg-indigo-50">
        <div class="grid gap-5 lg:grid-cols-[minmax(0,1fr)_minmax(16rem,22rem)] lg:items-start">
            <div>
                <p class="app-eyebrow border-indigo-200 bg-white text-indigo-900"><x-ui.icon name="route" class="h-4 w-4" /> Today&apos;s path</p>
                <h2 class="mt-3 app-section-title">{{ $definition['label'] }}</h2>
                <p class="mt-2 text-sm leading-6 text-slate-700">{{ $insight['primary'] }}</p>

                @if (($insight['suggested_season'] ?? $path['key']) !== $path['key'])
                    <button type="button" wire:click="switchToSuggestedPath('{{ $insight['suggested_season'] }}')" class="mt-4 btn-secondary border-indigo-200 bg-white text-indigo-900">
                        Switch path to {{ $seasons[$insight['suggested_season']]['label'] }}
                    </button>
                @endif
            </div>

            <div class="app-surface border-indigo-200 bg-white p-4">
                <div class="flex items-center justify-between gap-3">
                    <span class="text-sm font-bold text-slate-600">Daily completion</span>
                    <span class="text-sm font-black text-slate-950">{{ $checkIn->completedCount() }}/6</span>
                </div>
                <div class="mt-3 h-3 overflow-hidden rounded-full bg-slate-100">
                    <div class="h-full rounded-full bg-indigo-700" style="width: {{ $checkIn->progressPercent() }}%"></div>
                </div>
                <div class="mt-4 grid grid-cols-2 gap-2 text-center">
                    <div class="rounded-xl bg-sky-50 p-3">
                        <p class="text-xl font-black text-slate-950">{{ $insight['stats']['chapters'] }}</p>
                        <p class="text-xs font-bold uppercase tracking-normal text-sky-900">Chapters</p>
                    </div>
                    <div class="rounded-xl bg-amber-50 p-3">
                        <p class="text-xl font-black text-slate-950">{{ $insight['stats']['marked_verses'] }}</p>
                        <p class="text-xs font-bold uppercase tracking-normal text-amber-900">Marked verses</p>
                    </div>
                    <div class="rounded-xl bg-mauve-50 p-3">
                        <p class="text-xl font-black text-slate-950">{{ $insight['stats']['journal_entries'] }}</p>
                        <p class="text-xs font-bold uppercase tracking-normal text-mauve-900">Journal</p>
                    </div>
                    <div class="rounded-xl bg-rose-50 p-3">
                        <p class="text-xl font-black text-slate-950">{{ $insight['stats']['prayer_requests'] }}</p>
                        <p class="text-xs font-bold uppercase tracking-normal text-rose-900">Prayers</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="mt-5 grid gap-3 md:grid-cols-2 xl:grid-cols-3">
            @foreach ([
                'scripture' => ['label' => 'Scripture', 'text' => $definition['reference'], 'href' => $bibleBook ? route('bible', ['book' => $bibleBook->slug, 'chapter' => $definition['chapter']]) : null],
                'devotional' => ['label' => 'Devotional', 'text' => $devotional?->title ?? 'Recommended reading', 'href' => $devotional ? route('devotionals.show', $devotional->slug) : null],
                'affirmation' => ['label' => 'Affirmation', 'text' => $definition['affirmation'], 'href' => null],
                'prayer' => ['label' => 'Prayer', 'text' => $definition['prayer'], 'href' => route('prayer-sessions.index')],
                'journal' => ['label' => 'Journal', 'text' => $definition['journal_prompt'], 'href' => route('journal.index')],
                'action' => ['label' => 'Action', 'text' => $definition['action'], 'href' => null],
            ] as $stepKey => $step)
                @php $completedColumn = "{$stepKey}_completed_at"; @endphp
                <article class="rounded-xl border {{ $checkIn->{$completedColumn} ? 'border-emerald-200 bg-emerald-50' : 'border-white bg-white' }} p-4 shadow-sm">
                    <div class="flex items-start justify-between gap-3">
                        <div>
                            <p class="text-xs font-black uppercase tracking-normal {{ $checkIn->{$completedColumn} ? 'text-emerald-900' : 'text-indigo-900' }}">{{ $step['label'] }}</p>
                            <p class="mt-2 text-sm font-semibold leading-6 text-slate-800">{{ $step['text'] }}</p>
                        </div>
                        <button type="button" wire:click="completeStep('{{ $stepKey }}')" class="inline-flex h-9 w-9 shrink-0 items-center justify-center rounded-full border {{ $checkIn->{$completedColumn} ? 'border-emerald-300 bg-emerald-100 text-emerald-900' : 'border-slate-200 bg-white text-slate-700' }}">
                            <x-ui.icon name="check-circle" class="h-4 w-4" />
                        </button>
                    </div>
                    @if ($step['href'])
                        <a href="{{ $step['href'] }}" class="mt-3 inline-flex items-center gap-2 text-sm font-bold text-indigo-900 hover:text-slate-950">
                            Open <x-ui.icon name="chevron-right" class="h-4 w-4" />
                        </a>
                    @endif
                </article>
            @endforeach
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
