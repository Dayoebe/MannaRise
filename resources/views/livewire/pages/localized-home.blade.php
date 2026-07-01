<div class="space-y-7 sm:space-y-9">
    @php
        $daily = $content['daily'];
        $chapterUrl = $scripture['book_slug'] && $scripture['chapter'] ? route('bible', ['book' => $scripture['book_slug'], 'chapter' => $scripture['chapter']]) : null;
    @endphp

    <section class="grid gap-4 lg:grid-cols-[minmax(0,1.35fr)_minmax(18rem,0.85fr)] lg:items-stretch">
        <div class="app-panel overflow-hidden border-emerald-200 p-0 sm:p-0">
            <div class="color-strip rounded-none">
                <span class="bg-emerald-500"></span>
                <span class="bg-teal-500"></span>
                <span class="bg-sky-500"></span>
                <span class="bg-indigo-500"></span>
                <span class="bg-violet-500"></span>
                <span class="bg-amber-400"></span>
                <span class="bg-rose-400"></span>
            </div>

            <div class="p-5 sm:p-8">
                <p class="app-eyebrow border-emerald-200 bg-emerald-50 text-emerald-900"><x-ui.icon name="globe" class="h-4 w-4" /> {{ $content['hero_eyebrow'] }}</p>
                <h1 class="mt-4 max-w-3xl text-4xl font-black tracking-normal text-slate-950 sm:text-5xl">{{ $content['hero_title'] }}</h1>
                <p class="mt-4 max-w-2xl text-base leading-7 text-slate-600">{{ $content['hero_body'] }}</p>

                <div class="mt-6 flex flex-col gap-3 sm:flex-row sm:flex-wrap">
                    <a href="{{ $content['daily_url'] }}" class="btn-primary w-full sm:w-auto"><x-ui.icon name="star" class="h-4 w-4" /> {{ $content['primary_cta'] }}</a>
                    <a href="{{ $content['bible_url'] }}" class="btn-warm w-full sm:w-auto"><x-ui.icon name="book-open" class="h-4 w-4" /> {{ $content['secondary_cta'] }}</a>
                    <a href="{{ $content['prayer_url'] }}" class="btn-secondary w-full sm:w-auto"><x-ui.icon name="send" class="h-4 w-4" /> {{ $content['prayer_cta'] }}</a>
                </div>

                <div class="mt-7">
                    <p class="text-xs font-black uppercase tracking-[0.18em] text-slate-400">{{ $content['language_switcher'] }}</p>
                    <div class="mt-3 flex flex-wrap gap-2">
                        @foreach ($content['language_options'] as $option)
                            <a href="{{ $option['url'] }}" class="inline-flex min-h-10 items-center rounded-full border px-3 py-2 text-sm font-black {{ $option['current'] ? 'border-emerald-700 bg-emerald-700 text-white' : 'border-slate-200 bg-white text-slate-700 hover:border-emerald-300 hover:bg-emerald-50' }}">
                                {{ strtoupper($option['code']) }} · {{ $option['native_name'] }}
                            </a>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>

        <aside class="app-panel border-sky-200 bg-sky-50">
            <p class="app-eyebrow border-sky-200 bg-white text-sky-900"><x-ui.icon name="star" class="h-4 w-4" /> {{ $daily['page_eyebrow'] }}</p>
            <h2 class="mt-4 text-2xl font-black tracking-normal text-slate-950">{{ $daily['page_title'] }}</h2>
            <p class="mt-3 text-sm leading-6 text-slate-600">{{ $daily['page_intro'] }}</p>
            <a href="{{ $content['daily_url'] }}" class="mt-5 btn-primary w-full bg-sky-700 hover:bg-sky-800"><x-ui.icon name="share-2" class="h-4 w-4" /> {{ $daily['share_title'] }}</a>
        </aside>
    </section>

    <section class="grid gap-5 lg:grid-cols-[minmax(0,1fr)_minmax(0,1fr)]">
        <article class="app-panel border-blue-200 bg-blue-50">
            <p class="app-eyebrow border-blue-200 bg-white text-blue-900"><x-ui.icon name="book-open" class="h-4 w-4" /> {{ $daily['scripture_label'] }}</p>
            <blockquote class="mt-4 font-serif text-2xl font-semibold leading-9 text-slate-950">"{{ $scripture['text'] }}"</blockquote>
            <p class="mt-4 text-sm font-black tracking-normal text-blue-900">{{ $scripture['reference'] }}</p>

            @if ($chapterUrl)
                <a href="{{ $chapterUrl }}" class="mt-5 btn-secondary border-blue-200 text-blue-900 hover:bg-white">
                    {{ $daily['read_chapter'] }} <x-ui.icon name="chevron-right" class="h-4 w-4" />
                </a>
            @endif
        </article>

        <div class="grid gap-5">
            <article class="app-panel border-amber-200 bg-amber-50">
                <p class="app-eyebrow border-amber-200 bg-white text-amber-900"><x-ui.icon name="sparkles" class="h-4 w-4" /> {{ $daily['affirmation_label'] }}</p>
                <p class="mt-4 font-serif text-2xl font-semibold leading-9 text-slate-950">{{ $daily['affirmation_text'] }}</p>
                @if ($daily['affirmation_reference'])
                    <p class="mt-4 text-sm font-black tracking-normal text-amber-900">{{ $daily['affirmation_reference'] }}</p>
                @endif
            </article>

            <article class="app-panel border-rose-200 bg-rose-50">
                <p class="app-eyebrow border-rose-200 bg-white text-rose-900"><x-ui.icon name="heart" class="h-4 w-4" /> {{ $daily['prayer_label'] }}</p>
                <p class="mt-4 text-base font-semibold leading-7 text-slate-800">{{ $daily['prayer'] }}</p>
            </article>
        </div>
    </section>

    <section class="grid gap-5 lg:grid-cols-[minmax(0,0.8fr)_minmax(0,1.2fr)]">
        <article class="app-panel border-violet-200 bg-violet-50">
            <p class="app-eyebrow border-violet-200 bg-white text-violet-900"><x-ui.icon name="journal" class="h-4 w-4" /> {{ $daily['journal_label'] }}</p>
            <h2 class="mt-4 text-2xl font-black tracking-normal text-slate-950">{{ $daily['journal_prompt'] }}</h2>
            <p class="mt-3 text-sm font-black text-violet-900">{{ $daily['action_label'] }}</p>
            <p class="mt-2 text-sm leading-6 text-slate-600">{{ $daily['action'] }}</p>
        </article>

        <div>
            <p class="app-eyebrow border-lime-200 bg-lime-50 text-lime-900"><x-ui.icon name="layers" class="h-4 w-4" /> {{ $content['focus_title'] }}</p>
            <p class="mt-3 text-sm leading-6 text-slate-600">{{ $content['focus_intro'] }}</p>

            <div class="mt-4 grid gap-3 sm:grid-cols-3">
                @foreach ($content['focus_cards'] as $card)
                    <article class="app-surface border-lime-100 p-4">
                        <h3 class="font-black tracking-normal text-slate-950">{{ $card['title'] }}</h3>
                        <p class="mt-2 text-sm leading-6 text-slate-600">{{ $card['body'] }}</p>
                    </article>
                @endforeach
            </div>
        </div>
    </section>
</div>
