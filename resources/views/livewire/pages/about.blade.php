<div class="space-y-6 sm:space-y-8">
    <section class="app-panel overflow-hidden border-emerald-200 p-0 sm:p-0">
        <div class="color-strip rounded-none">
            <span class="bg-emerald-500"></span>
            <span class="bg-teal-500"></span>
            <span class="bg-sky-500"></span>
            <span class="bg-amber-400"></span>
            <span class="bg-fuchsia-500"></span>
        </div>
        <div class="p-5 sm:p-8">
            <p class="app-eyebrow border-emerald-200 bg-emerald-50 text-emerald-900"><x-ui.icon name="sparkles" class="h-4 w-4" /> About MannaRise</p>
            <h1 class="mt-4 max-w-4xl font-display text-4xl font-black leading-tight tracking-normal text-slate-950 sm:text-5xl">Daily devotionals, Bible study, prayer, and spiritual growth tools.</h1>
            <p class="mt-4 max-w-3xl text-base leading-7 text-slate-600">MannaRise is a Christian devotional and spiritual growth platform with daily Bible-based readings, prayer prompts, journaling, testimonies, memory verses, devotional plans, classic spiritual books, and community prayer spaces.</p>
        </div>
    </section>

    <section class="grid gap-4 lg:grid-cols-3">
        <article class="app-panel border-amber-200 bg-amber-50">
            <h2 class="flex items-center gap-2 text-xl font-black tracking-normal text-slate-950"><x-ui.icon name="book-open" class="h-5 w-5 text-amber-800" /> What it offers</h2>
            <p class="mt-3 text-sm leading-6 text-slate-700">Readers can follow devotionals, open the Bible reader, save reflections, practice memory verses, listen to audio devotionals, and use guided prayer sessions.</p>
        </article>
        <article class="app-panel border-sky-200 bg-sky-50">
            <h2 class="flex items-center gap-2 text-xl font-black tracking-normal text-slate-950"><x-ui.icon name="users" class="h-5 w-5 text-sky-800" /> Community focus</h2>
            <p class="mt-3 text-sm leading-6 text-slate-700">Public prayer rooms, the prayer wall, testimonies, and devotional plans help believers share requests, celebrate answered prayers, and grow with consistent rhythms.</p>
        </article>
        <article class="app-panel border-violet-200 bg-violet-50">
            <h2 class="flex items-center gap-2 text-xl font-black tracking-normal text-slate-950"><x-ui.icon name="library" class="h-5 w-5 text-violet-800" /> Resource library</h2>
            <p class="mt-3 text-sm leading-6 text-slate-700">The library and resource hub gather spiritual books, Bible resources, devotional guides, videos, audio, and public-domain learning materials where available.</p>
        </article>
    </section>

    <section class="app-panel border-slate-200 bg-white">
        <h2 class="text-2xl font-black tracking-normal text-slate-950">Start with the core pages</h2>
        <div class="mt-4 grid gap-3 sm:grid-cols-2 lg:grid-cols-4">
            <a href="{{ route('devotionals.index') }}" class="app-surface border-amber-100 p-4 hover:border-amber-300 hover:bg-amber-50">
                <span class="block font-black tracking-normal text-slate-950">Devotionals</span>
                <span class="mt-1 block text-sm leading-6 text-slate-600">Bible-based readings and reflection.</span>
            </a>
            <a href="{{ route('bible') }}" class="app-surface border-sky-100 p-4 hover:border-sky-300 hover:bg-sky-50">
                <span class="block font-black tracking-normal text-slate-950">Bible</span>
                <span class="mt-1 block text-sm leading-6 text-slate-600">Scripture reading and study mode.</span>
            </a>
            <a href="{{ route('resources.index') }}" class="app-surface border-emerald-100 p-4 hover:border-emerald-300 hover:bg-emerald-50">
                <span class="block font-black tracking-normal text-slate-950">Resources</span>
                <span class="mt-1 block text-sm leading-6 text-slate-600">Books, audio, videos, and guides.</span>
            </a>
            <a href="{{ route('prayer-rooms.index') }}" class="app-surface border-rose-100 p-4 hover:border-rose-300 hover:bg-rose-50">
                <span class="block font-black tracking-normal text-slate-950">Prayer rooms</span>
                <span class="mt-1 block text-sm leading-6 text-slate-600">Focused prayer with the community.</span>
            </a>
        </div>
    </section>
</div>
