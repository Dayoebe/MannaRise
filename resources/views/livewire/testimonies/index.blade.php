<div class="space-y-6 sm:space-y-8">
    <div class="app-panel overflow-hidden border-fuchsia-200 p-0 sm:p-0">
        <div class="color-strip rounded-none">
            <span class="bg-fuchsia-500"></span>
            <span class="bg-pink-500"></span>
            <span class="bg-rose-500"></span>
            <span class="bg-orange-500"></span>
            <span class="bg-amber-400"></span>
            <span class="bg-emerald-500"></span>
            <span class="bg-sky-500"></span>
        </div>
        <div class="flex flex-col gap-4 p-5 sm:p-6 md:flex-row md:items-end md:justify-between">
            <div>
                <p class="app-eyebrow border-fuchsia-200 bg-fuchsia-50 text-fuchsia-900"><x-ui.icon name="message-circle" class="h-4 w-4" /> Testimonies</p>
                <h1 class="mt-3 app-section-title">Testimonies</h1>
                <p class="mt-2 max-w-2xl text-sm leading-6 text-slate-600">Read approved testimonies from the MannaRise community and share your own story for review.</p>
            </div>
            <a href="{{ route('testimonies.submit') }}" class="btn-primary w-full bg-fuchsia-700 hover:bg-fuchsia-800 sm:w-auto"><x-ui.icon name="send" class="h-4 w-4" /> Share testimony</a>
        </div>
    </div>

    <label class="block max-w-md">
        <span class="mb-2 flex items-center gap-2 text-sm font-bold text-slate-700"><x-ui.icon name="search" class="h-4 w-4 text-fuchsia-700" /> Search testimonies</span>
        <input type="search" wire:model.live.debounce.300ms="search" placeholder="Search testimonies" class="field-input border-fuchsia-300 focus:border-fuchsia-600 focus:ring-fuchsia-100">
    </label>

    <div class="public-card-grid">
        @forelse ($testimonies as $testimony)
            <article class="app-panel public-card border-t-4 border-t-fuchsia-500 hover:border-fuchsia-300 even:border-t-pink-500">
                <p class="inline-flex items-center gap-2 rounded-full bg-fuchsia-50 px-3 py-1 text-xs font-bold uppercase tracking-normal text-fuchsia-900"><x-ui.icon name="message-circle" class="h-4 w-4" /> Testimony</p>
                <h2 class="mt-3 text-lg font-black tracking-normal text-slate-950">{{ $testimony->title }}</h2>
                <p class="mt-3 flex-1 text-sm leading-6 text-slate-700">{{ $testimony->body }}</p>
                <p class="mt-4 text-sm font-bold text-fuchsia-800">{{ $testimony->is_anonymous ? 'Anonymous' : ($testimony->name ?: 'MannaRise reader') }}</p>
            </article>
        @empty
            <p class="app-panel border-dashed border-slate-300 text-sm text-slate-600 md:col-span-2 xl:col-span-3">No approved testimonies match this search.</p>
        @endforelse
    </div>

    {{ $testimonies->links() }}
</div>
