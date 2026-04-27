<div class="space-y-6">
    <div class="flex flex-col gap-4 md:flex-row md:items-end md:justify-between">
        <div>
            <p class="text-sm font-semibold uppercase tracking-normal text-emerald-800">King James Version</p>
            <h1 class="mt-2 text-3xl font-semibold text-stone-950">Bible reader</h1>
            <p class="mt-2 max-w-2xl text-sm leading-6 text-stone-600">Read the full public-domain KJV Bible by book and chapter, or search across all verses.</p>
        </div>
        <a href="{{ route('library.index') }}" class="rounded-md border border-stone-300 bg-white px-4 py-2.5 text-sm font-semibold text-stone-800 hover:bg-stone-100">Open library</a>
    </div>

    @if ($books->isEmpty())
        <div class="rounded-lg border border-dashed border-stone-300 bg-white p-6 text-sm text-stone-600">
            The Bible has not been imported yet. Run `php artisan db:seed --class=BibleSeeder`.
        </div>
    @else
        <section class="rounded-lg border border-stone-200 bg-white p-5 shadow-sm">
            <div class="grid gap-4 md:grid-cols-[1fr_10rem_1fr]">
                <div>
                    <label class="block text-sm font-medium text-stone-700">Book</label>
                    <select wire:model.live="bookSlug" class="mt-1 w-full rounded-md border border-stone-300 px-3 py-2 text-sm outline-none focus:border-emerald-600 focus:ring-2 focus:ring-emerald-100">
                        @foreach ($books as $option)
                            <option value="{{ $option->slug }}">{{ $option->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-medium text-stone-700">Chapter</label>
                    <select wire:model.live="chapter" class="mt-1 w-full rounded-md border border-stone-300 px-3 py-2 text-sm outline-none focus:border-emerald-600 focus:ring-2 focus:ring-emerald-100">
                        @if ($book)
                            @for ($i = 1; $i <= $book->chapters; $i++)
                                <option value="{{ $i }}">{{ $i }}</option>
                            @endfor
                        @endif
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-medium text-stone-700">Search Bible</label>
                    <input type="search" wire:model.live.debounce.400ms="search" placeholder="Search words or phrase" class="mt-1 w-full rounded-md border border-stone-300 px-3 py-2 text-sm outline-none focus:border-emerald-600 focus:ring-2 focus:ring-emerald-100">
                </div>
            </div>
        </section>

        @if ($searchResults)
            <section class="rounded-lg border border-stone-200 bg-white p-5 shadow-sm">
                <div class="mb-4 flex items-center justify-between gap-3">
                    <h2 class="text-xl font-semibold text-stone-950">Search results</h2>
                    <span class="text-sm text-stone-500">{{ $searchResults->total() }} verses</span>
                </div>

                <div class="space-y-3">
                    @forelse ($searchResults as $result)
                        <article class="rounded-md border border-stone-200 p-4">
                            <p class="text-sm font-semibold text-emerald-800">{{ $result->book->name }} {{ $result->chapter }}:{{ $result->verse }}</p>
                            <p class="mt-2 text-base leading-7 text-stone-800">{{ $result->text }}</p>
                        </article>
                    @empty
                        <p class="text-sm text-stone-600">No verses matched that search.</p>
                    @endforelse
                </div>

                <div class="mt-5">{{ $searchResults->links() }}</div>
            </section>
        @endif

        <article class="rounded-lg border border-stone-200 bg-white p-6 shadow-sm sm:p-8">
            <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
                <div>
                    <p class="text-sm font-semibold uppercase tracking-normal text-emerald-800">{{ $book?->testament }}</p>
                    <h2 class="mt-1 text-3xl font-semibold text-stone-950">{{ $book?->name }} {{ $chapter }}</h2>
                </div>
                <div class="flex gap-2">
                    <button type="button" wire:click="previousChapter" class="rounded-md border border-stone-300 px-3 py-2 text-sm font-semibold text-stone-800 hover:bg-stone-100">Previous</button>
                    <button type="button" wire:click="nextChapter" class="rounded-md bg-emerald-700 px-3 py-2 text-sm font-semibold text-white hover:bg-emerald-800">Next</button>
                </div>
            </div>

            <div class="mt-6 space-y-3">
                @foreach ($verses as $verse)
                    <p class="text-lg leading-8 text-stone-800">
                        <sup class="mr-1 text-xs font-semibold text-emerald-800">{{ $verse->verse }}</sup>{{ $verse->text }}
                    </p>
                @endforeach
            </div>
        </article>
    @endif
</div>
