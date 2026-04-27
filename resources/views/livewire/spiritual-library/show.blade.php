<div class="grid gap-6 lg:grid-cols-[18rem_minmax(0,1fr)]">
    <aside class="rounded-lg border border-stone-200 bg-white p-5 shadow-sm">
        <a href="{{ route('library.index') }}" class="text-sm font-semibold text-emerald-800 hover:underline">Back to library</a>

        <h1 class="mt-4 text-2xl font-semibold text-stone-950">{{ $book->title }}</h1>
        <p class="mt-1 text-sm text-stone-500">{{ $book->author ?: 'Unknown author' }}</p>
        <p class="mt-3 text-sm leading-6 text-stone-600">{{ $book->description }}</p>

        <div class="mt-5 border-t border-stone-200 pt-4">
            <h2 class="text-sm font-semibold text-stone-950">Chapters</h2>
            <nav class="mt-3 space-y-1">
                @foreach ($chapters as $chapterOption)
                    <button type="button" wire:click="$set('chapter', {{ $chapterOption->chapter_number }})" class="block w-full rounded-md px-3 py-2 text-left text-sm hover:bg-stone-100 {{ $chapter === $chapterOption->chapter_number ? 'bg-emerald-50 font-semibold text-emerald-800' : 'text-stone-700' }}">
                        {{ $chapterOption->chapter_number }}. {{ $chapterOption->title }}
                    </button>
                @endforeach
            </nav>
        </div>
    </aside>

    <article class="rounded-lg border border-stone-200 bg-white p-6 shadow-sm sm:p-8">
        @if ($currentChapter)
            <div class="flex flex-col gap-4 sm:flex-row sm:items-start sm:justify-between">
                <div>
                    <p class="text-sm font-semibold uppercase tracking-normal text-emerald-800">Chapter {{ $currentChapter->chapter_number }}</p>
                    <h2 class="mt-2 text-3xl font-semibold text-stone-950">{{ $currentChapter->title }}</h2>
                </div>
                <div class="flex gap-2">
                    <button type="button" wire:click="previousChapter" class="rounded-md border border-stone-300 px-3 py-2 text-sm font-semibold text-stone-800 hover:bg-stone-100">Previous</button>
                    <button type="button" wire:click="nextChapter" class="rounded-md bg-emerald-700 px-3 py-2 text-sm font-semibold text-white hover:bg-emerald-800">Next</button>
                </div>
            </div>

            <div class="mt-8 space-y-5 text-lg leading-8 text-stone-800">
                @foreach (preg_split('/\n\n+/', trim($currentChapter->content)) as $paragraph)
                    <p>{{ $paragraph }}</p>
                @endforeach
            </div>
        @else
            <p class="text-sm text-stone-600">This book has no chapters yet.</p>
        @endif
    </article>
</div>
