<div class="space-y-6 sm:space-y-8">
    <div class="page-hero border-amber-200">
        <div class="color-strip rounded-none">
            <span class="bg-amber-400"></span>
            <span class="bg-orange-500"></span>
            <span class="bg-rose-500"></span>
            <span class="bg-emerald-500"></span>
            <span class="bg-sky-500"></span>
        </div>
        <div class="grid gap-4 p-5 sm:p-6 lg:grid-cols-[minmax(0,1fr)_minmax(16rem,0.55fr)] lg:items-end">
            <div>
                <p class="app-eyebrow border-amber-200 bg-amber-50 text-amber-900"><x-ui.icon name="star" class="h-4 w-4" /> Featured content</p>
                <h1 class="mt-3 app-section-title">Homepage and app highlights</h1>
                <p class="mt-2 max-w-2xl text-sm leading-6 text-slate-600">Choose which published devotionals are promoted on the home page, daily page, and app sections.</p>
            </div>
            <div class="rounded-2xl border border-amber-200 bg-amber-50 px-4 py-3 text-sm font-black text-amber-900">
                {{ $featuredSlots->where('is_active', true)->count() }} active slots
            </div>
        </div>
    </div>

    <section class="grid gap-4 xl:grid-cols-3">
        @foreach ($featuredSlots as $slot)
            <article class="app-panel border-amber-200">
                <div class="flex items-start justify-between gap-3">
                    <div>
                        <p class="app-eyebrow border-amber-200 bg-amber-50 text-amber-900"><x-ui.icon name="star" class="h-4 w-4" /> {{ $slot->statusLabel() }}</p>
                        <h2 class="mt-3 text-xl font-black tracking-normal text-slate-950">{{ $slot->label }}</h2>
                    </div>
                    <span class="rounded-full bg-slate-100 px-3 py-1 text-xs font-black uppercase tracking-normal text-slate-700">{{ str($slot->slot_key)->replace('_', ' ') }}</span>
                </div>

                <p class="mt-3 text-sm leading-6 text-slate-600">{{ $slot->description }}</p>

                @if ($slot->devotional)
                    <div class="mt-4 rounded-xl border border-emerald-100 bg-emerald-50 p-4">
                        <p class="text-xs font-black uppercase tracking-normal text-emerald-900">Current devotional</p>
                        <p class="mt-1 font-black tracking-normal text-slate-950">{{ $slot->devotional->title }}</p>
                        <p class="mt-1 text-sm font-bold text-emerald-800">{{ $slot->devotional->category?->name ?: 'Uncategorized' }}</p>
                    </div>
                @else
                    <p class="mt-4 rounded-xl border border-dashed border-slate-300 bg-slate-50 p-4 text-sm font-bold text-slate-600">No devotional assigned.</p>
                @endif

                <div class="mt-5 space-y-4">
                    <div>
                        <label class="block text-sm font-bold text-slate-700">Featured devotional</label>
                        <select wire:model="slotDevotionals.{{ $slot->slot_key }}" class="field-input mt-1 border-amber-300 focus:border-amber-600 focus:ring-amber-100">
                            <option value="">No devotional</option>
                            @foreach ($devotionals as $devotional)
                                <option value="{{ $devotional->id }}">{{ $devotional->title }}</option>
                            @endforeach
                        </select>
                    </div>

                    <label class="flex items-center gap-2 rounded-xl border border-amber-200 bg-amber-50 px-3 py-3 text-sm font-bold text-slate-700">
                        <input type="checkbox" wire:model="slotActive.{{ $slot->slot_key }}" class="rounded border-amber-300 text-amber-700 focus:ring-amber-600">
                        Active
                    </label>

                    <div class="grid gap-3 sm:grid-cols-2">
                        <div>
                            <label class="block text-sm font-bold text-slate-700">Starts at</label>
                            <input type="datetime-local" wire:model="slotStartsAt.{{ $slot->slot_key }}" class="field-input mt-1 border-amber-300 focus:border-amber-600 focus:ring-amber-100">
                        </div>
                        <div>
                            <label class="block text-sm font-bold text-slate-700">Ends at</label>
                            <input type="datetime-local" wire:model="slotEndsAt.{{ $slot->slot_key }}" class="field-input mt-1 border-amber-300 focus:border-amber-600 focus:ring-amber-100">
                        </div>
                    </div>

                    <div class="flex flex-wrap gap-2">
                        <button type="button" wire:click="saveSlot('{{ $slot->slot_key }}')" class="btn-primary bg-amber-700 hover:bg-amber-800">Save slot</button>
                        <button type="button" wire:click="clearSlot('{{ $slot->slot_key }}')" class="btn-secondary border-slate-300 px-3">Clear</button>
                    </div>
                </div>
            </article>
        @endforeach
    </section>
</div>
