<div class="mx-auto grid max-w-5xl gap-6 lg:grid-cols-[0.8fr_1.2fr]">
    <section class="rounded-lg border border-stone-200 bg-white p-6 shadow-sm">
        <h1 class="text-3xl font-semibold text-stone-950">Prayer request</h1>
        <p class="mt-3 text-sm leading-6 text-stone-600">
            Submit a request for prayer. Public requests can be included in community prayer lists, while private requests stay with the ministry team.
        </p>
    </section>

    <form wire:submit="submit" class="rounded-lg border border-stone-200 bg-white p-6 shadow-sm">
        <div class="grid gap-4 sm:grid-cols-2">
            <div>
                <label class="block text-sm font-medium text-stone-700">Name</label>
                <input type="text" wire:model="name" class="mt-1 w-full rounded-md border border-stone-300 px-3 py-2 text-sm outline-none focus:border-emerald-600 focus:ring-2 focus:ring-emerald-100">
                @error('name') <p class="mt-1 text-sm text-red-700">{{ $message }}</p> @enderror
            </div>
            <div>
                <label class="block text-sm font-medium text-stone-700">Email</label>
                <input type="email" wire:model="email" class="mt-1 w-full rounded-md border border-stone-300 px-3 py-2 text-sm outline-none focus:border-emerald-600 focus:ring-2 focus:ring-emerald-100">
                @error('email') <p class="mt-1 text-sm text-red-700">{{ $message }}</p> @enderror
            </div>
        </div>

        <div class="mt-4">
            <label class="block text-sm font-medium text-stone-700">Title</label>
            <input type="text" wire:model="title" class="mt-1 w-full rounded-md border border-stone-300 px-3 py-2 text-sm outline-none focus:border-emerald-600 focus:ring-2 focus:ring-emerald-100">
            @error('title') <p class="mt-1 text-sm text-red-700">{{ $message }}</p> @enderror
        </div>

        <div class="mt-4">
            <label class="block text-sm font-medium text-stone-700">Request</label>
            <textarea wire:model="body" rows="7" class="mt-1 w-full rounded-md border border-stone-300 px-3 py-2 text-sm outline-none focus:border-emerald-600 focus:ring-2 focus:ring-emerald-100"></textarea>
            @error('body') <p class="mt-1 text-sm text-red-700">{{ $message }}</p> @enderror
        </div>

        <label class="mt-4 flex items-center gap-2 text-sm text-stone-700">
            <input type="checkbox" wire:model="is_public" class="rounded border-stone-300 text-emerald-700 focus:ring-emerald-600">
            Share publicly
        </label>

        <button type="submit" class="mt-6 rounded-md bg-emerald-700 px-4 py-2.5 text-sm font-semibold text-white hover:bg-emerald-800">Submit request</button>
    </form>
</div>
