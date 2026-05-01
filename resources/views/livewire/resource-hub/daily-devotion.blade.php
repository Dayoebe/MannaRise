<div class="space-y-6 sm:space-y-8">
    <article class="app-panel overflow-hidden border-amber-200 bg-white p-0">
        <div class="color-strip rounded-none">
            <span class="bg-amber-400"></span>
            <span class="bg-emerald-500"></span>
            <span class="bg-sky-500"></span>
            <span class="bg-violet-500"></span>
        </div>
        <div class="grid gap-6 p-5 sm:p-8 lg:grid-cols-[minmax(0,1fr)_18rem]">
            <div>
                <p class="app-eyebrow border-amber-200 bg-amber-50 text-amber-900"><x-ui.icon name="sparkles" class="h-4 w-4" /> {{ $devotion->devotion_date->format('F j, Y') }}</p>
                <h1 class="mt-4 font-display text-4xl font-black leading-tight tracking-normal text-slate-950 sm:text-5xl">{{ $devotion->title }}</h1>
                @if ($devotion->bible_reference)
                    <p class="mt-4 text-xl font-black text-emerald-900">{{ $devotion->bible_reference }}</p>
                @endif
            </div>
            <div class="rounded-2xl border border-emerald-200 bg-emerald-50 p-4">
                @auth
                    <button type="button" wire:click="toggleBookmark" class="btn-secondary w-full border-emerald-200 bg-white">
                        <x-ui.icon name="bookmark" class="h-4 w-4" /> {{ $isBookmarked ? 'Bookmarked' : 'Bookmark' }}
                    </button>
                @else
                    <a href="{{ route('login') }}" class="btn-secondary w-full border-emerald-200 bg-white"><x-ui.icon name="log-in" class="h-4 w-4" /> Log in to bookmark</a>
                @endauth
                <div class="mt-4 grid grid-cols-2 gap-2">
                    @if ($previous)
                        <a href="{{ route('resources.devotion.show', $previous->slug) }}" class="btn-secondary border-slate-300 px-3"><x-ui.icon name="chevron-left" class="h-4 w-4" /> Prev</a>
                    @endif
                    @if ($next)
                        <a href="{{ route('resources.devotion.show', $next->slug) }}" class="btn-primary px-3">Next <x-ui.icon name="chevron-right" class="h-4 w-4" /></a>
                    @endif
                </div>
            </div>
        </div>
    </article>

    @if ($devotion->bible_text)
        <section class="app-panel border-emerald-200 bg-emerald-50">
            <h2 class="text-xl font-black tracking-normal text-slate-950">Scripture</h2>
            <p class="mt-3 text-lg leading-8 text-slate-800">{{ $devotion->bible_text }}</p>
            @if ($devotion->memory_verse)
                <p class="mt-4 rounded-2xl bg-white p-4 text-base font-black text-emerald-900">Memory verse: {{ $devotion->memory_verse }}</p>
            @endif
        </section>
    @endif

    <section class="app-panel border-slate-200 bg-white">
        <h2 class="text-xl font-black tracking-normal text-slate-950">Devotion guide</h2>
        <div class="reading-copy mt-4 max-w-4xl text-slate-800">{!! nl2br(e($devotion->devotion_text)) !!}</div>
    </section>

    <div class="grid gap-4 lg:grid-cols-3">
        @if ($devotion->prayer)
            <section class="app-panel border-rose-200 bg-rose-50">
                <h2 class="text-xl font-black tracking-normal text-slate-950">Prayer</h2>
                <p class="mt-3 text-sm leading-7 text-slate-700">{{ $devotion->prayer }}</p>
            </section>
        @endif
        @if ($devotion->reflection_questions)
            <section class="app-panel border-sky-200 bg-sky-50">
                <h2 class="text-xl font-black tracking-normal text-slate-950">Reflect</h2>
                <ul class="mt-3 space-y-2 text-sm leading-6 text-slate-700">
                    @foreach ($devotion->reflection_questions as $question)
                        <li class="rounded-xl bg-white p-3">{{ $question }}</li>
                    @endforeach
                </ul>
            </section>
        @endif
        @if ($devotion->action_point)
            <section class="app-panel border-amber-200 bg-amber-50">
                <h2 class="text-xl font-black tracking-normal text-slate-950">Action point</h2>
                <p class="mt-3 text-sm leading-7 text-slate-700">{{ $devotion->action_point }}</p>
            </section>
        @endif
    </div>
</div>
