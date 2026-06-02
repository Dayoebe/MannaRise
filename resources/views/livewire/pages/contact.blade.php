<div class="space-y-6 sm:space-y-8">
    <section class="app-panel overflow-hidden border-sky-200 p-0 sm:p-0">
        <div class="color-strip rounded-none">
            <span class="bg-sky-500"></span>
            <span class="bg-emerald-500"></span>
            <span class="bg-amber-400"></span>
            <span class="bg-rose-500"></span>
        </div>
        <div class="p-5 sm:p-8">
            <p class="app-eyebrow border-sky-200 bg-sky-50 text-sky-900"><x-ui.icon name="mail" class="h-4 w-4" /> Contact</p>
            <h1 class="mt-4 max-w-4xl font-display text-4xl font-black leading-tight tracking-normal text-slate-950 sm:text-5xl">Contact MannaRise</h1>
            <p class="mt-4 max-w-3xl text-base leading-7 text-slate-600">Use the public contact details configured for the site, or continue through the prayer and testimony pages for community-facing spiritual support.</p>
        </div>
    </section>

    <section class="grid gap-4 lg:grid-cols-[minmax(0,1fr)_minmax(18rem,24rem)]">
        <div class="app-panel border-emerald-200 bg-emerald-50">
            <h2 class="flex items-center gap-2 text-xl font-black tracking-normal text-slate-950"><x-ui.icon name="mail" class="h-5 w-5 text-emerald-800" /> Public contact</h2>
            @if ($contactEmail)
                <p class="mt-3 text-sm leading-6 text-slate-700">The configured public email for MannaRise is:</p>
                <a href="mailto:{{ $contactEmail }}" class="mt-4 inline-flex items-center gap-2 rounded-full border border-emerald-200 bg-white px-4 py-2.5 text-sm font-black text-emerald-900 hover:bg-emerald-100">
                    <x-ui.icon name="mail" class="h-4 w-4" /> {{ $contactEmail }}
                </a>
            @else
                <p class="mt-3 text-sm leading-6 text-slate-700">No public contact email is configured in the site settings yet.</p>
            @endif
        </div>

        <aside class="app-panel border-rose-200 bg-rose-50">
            <h2 class="flex items-center gap-2 text-xl font-black tracking-normal text-slate-950"><x-ui.icon name="heart" class="h-5 w-5 text-rose-800" /> Prayer support</h2>
            <p class="mt-3 text-sm leading-6 text-slate-700">Prayer requests and testimonies are handled through the public community pages.</p>
            <div class="mt-4 grid gap-2">
                <a href="{{ route('prayer-requests.submit') }}" class="btn-primary w-full bg-rose-700 hover:bg-rose-800"><x-ui.icon name="send" class="h-4 w-4" /> Submit prayer request</a>
                <a href="{{ route('testimonies.submit') }}" class="btn-secondary w-full border-rose-200 bg-white"><x-ui.icon name="message-circle" class="h-4 w-4" /> Share testimony</a>
            </div>
        </aside>
    </section>
</div>
