<div class="grid gap-5 lg:grid-cols-[minmax(0,19rem)_minmax(0,1fr)] lg:items-start">
    <aside class="app-panel border-cyan-200 bg-cyan-50 lg:sticky lg:top-36">
        <a href="{{ route('library.index') }}" class="inline-flex items-center gap-2 text-sm font-bold text-cyan-900 hover:text-cyan-700">
            <x-ui.icon name="chevron-left" class="h-4 w-4" /> Back to library
        </a>

        <h1 class="mt-4 text-2xl font-black tracking-normal text-slate-950">{{ $book->title }}</h1>
        <p class="mt-1 text-sm font-bold text-slate-500">{{ $book->author ?: 'Unknown author' }}</p>
        <p class="mt-3 text-sm leading-6 text-slate-700">{{ $book->description }}</p>

        <div class="mt-5 border-t border-cyan-200 pt-4">
            <h2 class="flex items-center gap-2 text-sm font-black tracking-normal text-slate-950"><x-ui.icon name="book-open" class="h-4 w-4 text-cyan-800" /> Chapters</h2>
            <nav class="mt-3 grid max-h-72 gap-2 overflow-y-auto pr-1 sm:grid-cols-2 lg:max-h-[calc(100vh-17rem)] lg:grid-cols-1">
                @foreach ($chapters as $chapterOption)
                    <button type="button" wire:click="$set('chapter', {{ $chapterOption->chapter_number }})" class="min-h-11 w-full rounded-xl border px-3 py-2 text-left text-sm transition {{ $chapter === $chapterOption->chapter_number ? 'border-emerald-300 bg-emerald-700 font-black text-white shadow-sm' : 'border-cyan-200 bg-white font-bold text-slate-700 hover:border-cyan-400 hover:bg-cyan-100' }}">
                        <span class="block truncate">{{ $chapterOption->chapter_number }}. {{ $chapterOption->title }}</span>
                    </button>
                @endforeach
            </nav>
        </div>
    </aside>

    <article class="app-panel overflow-hidden border-olive-200 bg-white p-0 sm:p-0">
        @if ($currentChapter)
            <div class="color-strip rounded-none">
                <span class="bg-olive-500"></span>
                <span class="bg-emerald-500"></span>
                <span class="bg-teal-500"></span>
                <span class="bg-cyan-500"></span>
                <span class="bg-sky-500"></span>
                <span class="bg-violet-500"></span>
            </div>
            <div class="flex flex-col gap-4 p-5 sm:flex-row sm:items-start sm:justify-between sm:p-8">
                <div>
                    <p class="inline-flex items-center gap-2 rounded-full border border-olive-200 bg-olive-50 px-3 py-1 text-sm font-black uppercase tracking-normal text-olive-800"><x-ui.icon name="book-open" class="h-4 w-4" /> Chapter {{ $currentChapter->chapter_number }}</p>
                    <h2 class="mt-3 text-3xl font-black tracking-normal text-slate-950 sm:text-4xl">{{ $currentChapter->title }}</h2>
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

            <div class="reading-copy space-y-5 px-5 pb-6 sm:px-8 sm:pb-8">
                @foreach (preg_split('/\n\n+/', trim($currentChapter->content)) as $paragraph)
                    <p>{{ $paragraph }}</p>
                @endforeach
            </div>
        @else
            <p class="p-5 text-sm text-slate-600 sm:p-8">This book has no chapters yet.</p>
        @endif
    </article>
</div>
