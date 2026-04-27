<div class="space-y-6 sm:space-y-8">
    <div wire:loading.flex class="loading-hint items-center gap-2"><x-ui.icon name="headphones" class="h-4 w-4 animate-pulse" /> Loading audio devotionals...</div>

    <div class="page-hero border-violet-200">
        <div class="color-strip rounded-none"><span class="bg-violet-500"></span><span class="bg-purple-500"></span><span class="bg-fuchsia-500"></span><span class="bg-emerald-500"></span><span class="bg-amber-400"></span></div>
        <div class="page-hero-body">
            <div>
                <p class="app-eyebrow border-violet-200 bg-violet-50 text-violet-900"><x-ui.icon name="headphones" class="h-4 w-4" /> Listen and grow</p>
                <h1 class="mt-3 app-section-title">Audio devotionals</h1>
                <p class="mt-2 max-w-2xl text-sm leading-6 text-slate-600">Listen while commuting, preparing for work, resting, or praying through your day.</p>
            </div>
            <input type="search" wire:model.live.debounce.300ms="search" placeholder="Search audio devotionals" class="field-input border-violet-300 focus:border-violet-600 focus:ring-violet-100">
        </div>
    </div>

    <div class="public-card-grid">
        @forelse ($audioDevotionals as $audio)
            <article class="app-panel app-panel-hover public-card border-t-4 border-t-violet-500">
                <div class="flex flex-wrap items-center justify-between gap-3">
                    <span class="status-pill bg-violet-50 text-violet-900"><x-ui.icon name="headphones" class="h-4 w-4" /> {{ $audio->duration_label }}</span>
                    @if ($audio->speaker)<span class="rounded-full bg-emerald-50 px-3 py-1 text-xs font-bold text-emerald-900">{{ $audio->speaker }}</span>@endif
                </div>
                <h2 class="mt-3 text-xl font-black tracking-normal text-slate-950">{{ $audio->title }}</h2>
                @if ($audio->description)<p class="mt-3 flex-1 text-sm leading-6 text-slate-600">{{ \Illuminate\Support\Str::limit($audio->description, 150) }}</p>@endif
                @if ($audio->devotional)<a href="{{ route('devotionals.show', $audio->devotional->slug) }}" class="mt-3 text-sm font-bold text-emerald-800 hover:underline">Linked devotional: {{ $audio->devotional->title }}</a>@endif
                <audio class="mt-4 w-full" controls preload="none" src="{{ $audio->audio_url }}"></audio>
            </article>
        @empty
            <div class="md:col-span-2 xl:col-span-3"><x-ui.empty-state title="No audio devotionals yet" message="Published audio devotionals will appear here when they are added from the admin dashboard." /></div>
        @endforelse
    </div>

    <div>{{ $audioDevotionals->links() }}</div>
</div>
