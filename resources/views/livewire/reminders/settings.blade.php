<div class="space-y-6 sm:space-y-8">
    <div class="page-hero border-emerald-200">
        <div class="color-strip rounded-none"><span class="bg-emerald-500"></span><span class="bg-teal-500"></span><span class="bg-sky-500"></span><span class="bg-amber-400"></span></div>
        <div class="p-5 sm:p-6">
            <p class="app-eyebrow"><x-ui.icon name="bell" class="h-4 w-4" /> Reminders</p>
            <h1 class="mt-3 app-section-title">Devotional reminders</h1>
            <p class="mt-2 max-w-2xl text-sm leading-6 text-slate-600">Choose when MannaRise should remind you to read, reflect, and pray.</p>
        </div>
    </div>

    <form wire:submit="save" class="app-panel border-emerald-200 bg-emerald-50">
        <div class="grid gap-4 md:grid-cols-2">
            <div>
                <label class="block text-sm font-bold text-slate-700">Reminder title</label>
                <input type="text" wire:model="title" class="field-input mt-1 border-emerald-300 focus:border-emerald-600 focus:ring-emerald-100">
                @error('title') <p class="mt-1 text-sm text-red-700">{{ $message }}</p> @enderror
            </div>
            <div>
                <label class="block text-sm font-bold text-slate-700">Reminder time</label>
                <input type="time" wire:model="remind_at" class="field-input mt-1 border-emerald-300 focus:border-emerald-600 focus:ring-emerald-100">
                @error('remind_at') <p class="mt-1 text-sm text-red-700">{{ $message }}</p> @enderror
            </div>
            <div>
                <label class="block text-sm font-bold text-slate-700">Timezone</label>
                <input type="text" wire:model="timezone" class="field-input mt-1 border-emerald-300 focus:border-emerald-600 focus:ring-emerald-100">
                @error('timezone') <p class="mt-1 text-sm text-red-700">{{ $message }}</p> @enderror
            </div>
        </div>

        <div class="mt-5 flex flex-wrap gap-3">
            <label class="flex items-center gap-2 rounded-xl border border-emerald-200 bg-white px-3 py-3 text-sm font-bold text-slate-700"><input type="checkbox" wire:model="email_enabled" class="rounded border-emerald-300 text-emerald-700 focus:ring-emerald-600"> Email reminder</label>
            <label class="flex items-center gap-2 rounded-xl border border-emerald-200 bg-white px-3 py-3 text-sm font-bold text-slate-700"><input type="checkbox" wire:model="push_enabled" class="rounded border-emerald-300 text-emerald-700 focus:ring-emerald-600"> Push reminder ready</label>
            <label class="flex items-center gap-2 rounded-xl border border-emerald-200 bg-white px-3 py-3 text-sm font-bold text-slate-700"><input type="checkbox" wire:model="is_active" class="rounded border-emerald-300 text-emerald-700 focus:ring-emerald-600"> Active</label>
        </div>

        <div class="mt-5 flex flex-col gap-3 sm:flex-row sm:items-center">
            <button type="submit" class="btn-primary w-full sm:w-auto"><x-ui.icon name="bell" class="h-4 w-4" /> Save reminder</button>
            <p class="text-sm text-slate-600">Email notifications are supported now. Push reminders are prepared for later browser push subscription work.</p>
        </div>
    </form>
</div>
