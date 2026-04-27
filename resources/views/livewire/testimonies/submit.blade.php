<div class="mx-auto grid max-w-5xl gap-5 lg:grid-cols-[0.8fr_1.2fr] lg:items-start">
    <section class="app-panel overflow-hidden border-fuchsia-200 p-0 sm:p-0">
        <div class="color-strip rounded-none">
            <span class="bg-fuchsia-500"></span>
            <span class="bg-pink-500"></span>
            <span class="bg-rose-500"></span>
            <span class="bg-orange-500"></span>
            <span class="bg-emerald-500"></span>
        </div>
        <div class="p-5 sm:p-6">
            <p class="app-eyebrow border-fuchsia-200 bg-fuchsia-50 text-fuchsia-900"><x-ui.icon name="message-circle" class="h-4 w-4" /> Share testimony</p>
            <h1 class="mt-3 app-section-title">Share testimony</h1>
            <p class="mt-3 text-sm leading-6 text-slate-600">
                Testimonies are reviewed before appearing publicly so the community can be encouraged with care.
            </p>
        </div>
    </section>

    <form wire:submit="submit" class="app-panel border-fuchsia-200 bg-fuchsia-50">
        <div>
            <label class="block text-sm font-bold text-slate-700">Name</label>
            <input type="text" wire:model="name" class="field-input mt-1 border-fuchsia-300 focus:border-fuchsia-600 focus:ring-fuchsia-100">
            @error('name') <p class="mt-1 text-sm text-red-700">{{ $message }}</p> @enderror
        </div>

        <div class="mt-4">
            <label class="block text-sm font-bold text-slate-700">Title</label>
            <input type="text" wire:model="title" class="field-input mt-1 border-fuchsia-300 focus:border-fuchsia-600 focus:ring-fuchsia-100">
            @error('title') <p class="mt-1 text-sm text-red-700">{{ $message }}</p> @enderror
        </div>

        <div class="mt-4">
            <label class="block text-sm font-bold text-slate-700">Testimony</label>
            <textarea wire:model="body" rows="8" class="field-input mt-1 border-fuchsia-300 focus:border-fuchsia-600 focus:ring-fuchsia-100"></textarea>
            @error('body') <p class="mt-1 text-sm text-red-700">{{ $message }}</p> @enderror
        </div>

        <label class="mt-4 flex items-center gap-2 rounded-xl border border-fuchsia-200 bg-white px-3 py-3 text-sm font-bold text-slate-700">
            <input type="checkbox" wire:model="is_anonymous" class="rounded border-fuchsia-300 text-fuchsia-700 focus:ring-fuchsia-600">
            <x-ui.icon name="shield" class="h-4 w-4" /> Keep my name anonymous publicly
        </label>

        <button type="submit" class="mt-6 btn-primary w-full bg-fuchsia-700 hover:bg-fuchsia-800 sm:w-auto"><x-ui.icon name="send" class="h-4 w-4" /> Submit testimony</button>
    </form>
</div>
