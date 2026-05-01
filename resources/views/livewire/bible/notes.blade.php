<div class="space-y-6 sm:space-y-8">
    <section class="page-hero border-blue-200">
        <div class="color-strip rounded-none">
            <span class="bg-blue-500"></span>
            <span class="bg-sky-500"></span>
            <span class="bg-emerald-500"></span>
            <span class="bg-amber-400"></span>
        </div>
        <div class="page-hero-body">
            <div>
                <p class="app-eyebrow border-blue-200 bg-blue-50 text-blue-900"><x-ui.icon name="bookmark" class="h-4 w-4" /> Bible notes</p>
                <h1 class="mt-3 app-section-title">Your saved Scripture</h1>
                <p class="mt-2 max-w-2xl text-sm leading-6 text-slate-600">Review the verses you saved, highlighted, or wrote notes on while reading.</p>
            </div>
            <a href="{{ route('bible') }}" class="btn-primary w-full sm:w-auto"><x-ui.icon name="book-open" class="h-4 w-4" /> Open Bible</a>
        </div>
    </section>

    <section class="app-panel border-sky-200 bg-sky-50">
        <div class="grid gap-2 sm:grid-cols-4">
            @foreach ([['all', 'All'], ['notes', 'Notes'], ['bookmarks', 'Saved'], ['highlights', 'Highlights']] as [$key, $label])
                <button type="button" wire:click="$set('filter', '{{ $key }}')" class="rounded-xl border px-3 py-3 text-sm font-black transition {{ $filter === $key ? 'border-sky-700 bg-sky-700 text-white' : 'border-white bg-white text-slate-700 hover:border-sky-200' }}">
                    {{ $label }} <span class="ml-1 opacity-80">{{ $counts[$key] }}</span>
                </button>
            @endforeach
        </div>
    </section>

    <section class="grid gap-4 lg:grid-cols-2">
        @forelse ($engagements as $engagement)
            @php
                $verse = $engagement->verse;
                $book = $verse?->book;
            @endphp
            <article class="app-panel border-l-4 {{ $engagement->highlight_color ? 'border-l-amber-400' : 'border-l-sky-400' }}">
                <div class="flex flex-wrap items-center justify-between gap-3">
                    <p class="font-black tracking-normal text-slate-950">{{ $book?->name }} {{ $verse?->chapter }}:{{ $verse?->verse }}</p>
                    <div class="flex flex-wrap gap-2">
                        @if ($engagement->bookmarked_at)
                            <span class="rounded-full bg-sky-50 px-3 py-1 text-xs font-bold uppercase tracking-normal text-sky-900">Saved</span>
                        @endif
                        @if ($engagement->highlight_color)
                            <span class="rounded-full bg-amber-50 px-3 py-1 text-xs font-bold uppercase tracking-normal text-amber-900">{{ $engagement->highlight_color }}</span>
                        @endif
                    </div>
                </div>
                <p class="mt-3 font-serif text-base leading-7 text-slate-800">{{ $verse?->text }}</p>
                @if ($engagement->note)
                    <div class="mt-4 rounded-xl border border-slate-100 bg-slate-50 p-4">
                        <p class="text-xs font-black uppercase tracking-normal text-slate-500">Personal note</p>
                        <p class="mt-2 text-sm leading-6 text-slate-700">{{ $engagement->note }}</p>
                    </div>
                @endif
                <div class="mt-4 flex flex-wrap gap-2">
                    @if ($book && $verse)
                        <a href="{{ route('bible', ['book' => $book->slug, 'chapter' => $verse->chapter]).'#verse-'.$verse->verse }}" class="btn-secondary border-sky-200 px-3">Open chapter</a>
                    @endif
                    @if ($engagement->note)
                        <button type="button" wire:click="deleteNote({{ $engagement->id }})" wire:confirm="Remove this note?" class="btn-secondary border-rose-200 px-3 text-rose-800 hover:bg-rose-50">Remove note</button>
                    @endif
                </div>
            </article>
        @empty
            <div class="app-panel border-dashed border-slate-300 text-sm text-slate-600 lg:col-span-2">
                No saved Bible notes yet. Open the Bible reader and use the small verse action icon beside any verse.
            </div>
        @endforelse
    </section>

    <div>{{ $engagements->links() }}</div>
</div>
