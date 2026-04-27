<div class="space-y-6">
    <div class="flex flex-col gap-4 md:flex-row md:items-end md:justify-between">
        <div>
            <h1 class="text-3xl font-semibold text-stone-950">Testimonies</h1>
            <p class="mt-2 max-w-2xl text-sm leading-6 text-stone-600">Read approved testimonies from the MannaRise community and share your own story for review.</p>
        </div>
        <a href="{{ route('testimonies.submit') }}" class="rounded-md bg-emerald-700 px-4 py-2.5 text-sm font-semibold text-white hover:bg-emerald-800">Share testimony</a>
    </div>

    <input type="search" wire:model.live.debounce.300ms="search" placeholder="Search testimonies" class="w-full rounded-md border border-stone-300 bg-white px-3 py-2 text-sm outline-none focus:border-emerald-600 focus:ring-2 focus:ring-emerald-100 md:max-w-md">

    <div class="grid gap-4 md:grid-cols-2 xl:grid-cols-3">
        @forelse ($testimonies as $testimony)
            <article class="rounded-lg border border-stone-200 bg-white p-5 shadow-sm">
                <p class="text-xs font-semibold uppercase tracking-normal text-emerald-800">Testimony</p>
                <h2 class="mt-2 text-lg font-semibold text-stone-950">{{ $testimony->title }}</h2>
                <p class="mt-3 text-sm leading-6 text-stone-700">{{ $testimony->body }}</p>
                <p class="mt-4 text-sm font-medium text-stone-500">{{ $testimony->is_anonymous ? 'Anonymous' : ($testimony->name ?: 'MannaRise reader') }}</p>
            </article>
        @empty
            <p class="rounded-lg border border-dashed border-stone-300 bg-white p-6 text-sm text-stone-600 md:col-span-2 xl:col-span-3">No approved testimonies match this search.</p>
        @endforelse
    </div>

    {{ $testimonies->links() }}
</div>
