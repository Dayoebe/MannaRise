<div class="space-y-6 sm:space-y-8">
    <div class="app-panel overflow-hidden border-blue-200 p-0 sm:p-0">
        <div class="color-strip rounded-none">
            <span class="bg-blue-500"></span>
            <span class="bg-indigo-500"></span>
            <span class="bg-violet-500"></span>
            <span class="bg-purple-500"></span>
            <span class="bg-emerald-500"></span>
            <span class="bg-amber-400"></span>
        </div>
        <div class="flex flex-col gap-4 p-5 sm:p-6 md:flex-row md:items-end md:justify-between">
            <div>
                <p class="app-eyebrow border-blue-200 bg-blue-50 text-blue-900"><x-ui.icon name="book-open" class="h-4 w-4" /> King James Version</p>
                <h1 class="mt-3 app-section-title">Bible reader</h1>
                <p class="mt-2 max-w-2xl text-sm leading-6 text-slate-600">Read the full public-domain KJV Bible by book and chapter, or search across all verses.</p>
            </div>
            <a href="{{ route('library.index') }}" class="btn-secondary w-full border-cyan-200 text-cyan-900 hover:bg-cyan-50 sm:w-auto"><x-ui.icon name="library" class="h-4 w-4" /> Open library</a>
        </div>
    </div>

    @if ($books->isEmpty())
        <div class="app-panel border-dashed border-slate-300 text-sm text-slate-600">
            The Bible has not been imported yet. Run `php artisan db:seed --class=BibleSeeder`.
        </div>
    @else
        <section class="app-panel border-sky-200 bg-sky-50">
            <div class="grid gap-4 md:grid-cols-[minmax(0,1fr)_8rem_minmax(0,1fr)]">
                <div>
                    <label class="flex items-center gap-2 text-sm font-bold text-slate-700"><x-ui.icon name="book-open" class="h-4 w-4 text-blue-700" /> Book</label>
                    <select wire:model.live="bookSlug" class="field-input mt-1 border-sky-300 focus:border-blue-600 focus:ring-blue-100">
                        @foreach ($books as $option)
                            <option value="{{ $option->slug }}">{{ $option->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="flex items-center gap-2 text-sm font-bold text-slate-700"><x-ui.icon name="bookmark" class="h-4 w-4 text-blue-700" /> Chapter</label>
                    <select wire:model.live="chapter" class="field-input mt-1 border-sky-300 focus:border-blue-600 focus:ring-blue-100">
                        @if ($book)
                            @for ($i = 1; $i <= $book->chapters; $i++)
                                <option value="{{ $i }}">{{ $i }}</option>
                            @endfor
                        @endif
                    </select>
                </div>

                <div>
                    <label class="flex items-center gap-2 text-sm font-bold text-slate-700"><x-ui.icon name="search" class="h-4 w-4 text-blue-700" /> Search Bible</label>
                    <input type="search" wire:model.live.debounce.400ms="search" placeholder="Search words or phrase" class="field-input mt-1 border-sky-300 focus:border-blue-600 focus:ring-blue-100">
                </div>
            </div>
        </section>

        @if ($searchResults)
            <section class="app-panel border-mauve-200 bg-mauve-50">
                <div class="mb-4 flex flex-col gap-2 sm:flex-row sm:items-center sm:justify-between">
                    <h2 class="flex items-center gap-2 text-xl font-black tracking-normal text-slate-950"><x-ui.icon name="search" class="h-5 w-5 text-mauve-700" /> Search results</h2>
                    <span class="inline-flex w-fit rounded-full bg-white px-3 py-1 text-sm font-bold text-mauve-800 shadow-sm">{{ $searchResults->total() }} verses</span>
                </div>

                <div class="space-y-3">
                    @forelse ($searchResults as $result)
                        <article class="rounded-xl border border-mauve-200 bg-white p-4 shadow-sm">
                            <p class="text-sm font-black tracking-normal text-mauve-800">{{ $result->book->name }} {{ $result->chapter }}:{{ $result->verse }}</p>
                            <p class="mt-2 text-base leading-7 text-slate-800">{{ $result->text }}</p>
                        </article>
                    @empty
                        <p class="rounded-xl border border-dashed border-mauve-300 bg-white p-4 text-sm text-slate-600">No verses matched that search.</p>
                    @endforelse
                </div>

                <div class="mt-5">{{ $searchResults->links() }}</div>
            </section>
        @endif

        <article class="app-panel border-olive-200 bg-white p-5 sm:p-8">
            <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
                <div>
                    <p class="inline-flex items-center gap-2 rounded-full border border-olive-200 bg-olive-50 px-3 py-1 text-sm font-black uppercase tracking-normal text-olive-800"><x-ui.icon name="book-open" class="h-4 w-4" /> {{ $book?->testament }}</p>
                    <h2 class="mt-3 text-3xl font-black tracking-normal text-slate-950 sm:text-4xl">{{ $book?->name }} {{ $chapter }}</h2>
                </div>
                <div class="grid grid-cols-2 gap-2 sm:flex">
                    <button type="button" wire:click="previousChapter" class="btn-secondary border-slate-300 px-3">
                        <x-ui.icon name="chevron-left" class="h-4 w-4" /> Previous
</button>
                    <button type="button" wire:click="nextChapter" class="btn-primary px-3">
                        Next <x-ui.icon name="chevron-right" class="h-4 w-4" />
</button>
                </div>
            </div>

            <div class="mt-6 space-y-4">
                @foreach ($verses as $verse)
                    <p class="text-lg leading-8 text-slate-800">
                        <sup class="mr-2 inline-flex h-6 min-w-6 items-center justify-center rounded-full bg-amber-100 px-1 text-xs font-black text-amber-900">{{ $verse->verse }}</sup>{{ $verse->text }}
</p>
                @endforeach
            </div>
        </article>
    @endif
</div>
