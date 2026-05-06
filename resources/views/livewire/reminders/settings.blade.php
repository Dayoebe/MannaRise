<div class="space-y-6 sm:space-y-8">
    <div class="page-hero border-emerald-200">
        <div class="color-strip rounded-none"><span class="bg-emerald-500"></span><span class="bg-teal-500"></span><span class="bg-sky-500"></span><span class="bg-amber-400"></span></div>
        <div class="p-5 sm:p-6">
            <p class="app-eyebrow"><x-ui.icon name="bell" class="h-4 w-4" /> Reminders</p>
            <h1 class="mt-3 app-section-title">Spiritual rhythm reminders</h1>
            <p class="mt-2 max-w-2xl text-sm leading-6 text-slate-600">Choose when MannaRise should point you back to today&apos;s personalized path, send gentle reset nudges, and deliver your weekly digest.</p>
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

        <div class="mt-5 grid gap-3 md:grid-cols-3">
            @foreach ([
                'email_enabled' => ['Email reminder', 'bell', $email_enabled],
                'push_enabled' => ['In-app reminder', 'message-circle', $push_enabled],
                'is_active' => ['Reminder active', 'check-circle', $is_active],
            ] as $field => [$label, $icon, $enabled])
                <button type="button" wire:click="toggleChannel('{{ $field }}')" wire:key="channel-{{ $field }}" @class([
                    'flex min-h-16 items-center justify-between gap-3 rounded-xl border px-4 py-3 text-left text-sm font-bold transition',
                    'border-emerald-500 bg-white text-emerald-950 shadow-sm' => $enabled,
                    'border-slate-200 bg-white/70 text-slate-600 hover:bg-white' => ! $enabled,
                ])>
                    <span class="flex items-center gap-3">
                        <x-ui.icon :name="$icon" class="h-4 w-4" />
                        {{ $label }}
                    </span>
                    <span @class([
                        'relative inline-flex h-6 w-11 items-center rounded-full transition',
                        'bg-emerald-600' => $enabled,
                        'bg-slate-300' => ! $enabled,
                    ])>
                        <span @class([
                            'inline-block h-5 w-5 rounded-full bg-white shadow transition',
                            'translate-x-5' => $enabled,
                            'translate-x-1' => ! $enabled,
                        ])></span>
                    </span>
                </button>
            @endforeach
        </div>

        <div class="mt-6 grid gap-5 lg:grid-cols-2">
            <div>
                <h2 class="text-lg font-black tracking-normal text-slate-950">Reminder types</h2>
                <div class="mt-3 grid gap-2 sm:grid-cols-2">
                    @foreach ($typeOptions as $key => [$label, $icon])
                        @php($selected = in_array($key, $reminderTypes, true))
                        <button type="button" wire:click="toggleReminderType('{{ $key }}')" wire:key="type-{{ $key }}" @class([
                            'flex min-h-14 items-center justify-between gap-3 rounded-xl border px-3 py-3 text-left text-sm font-bold transition',
                            'border-emerald-500 bg-white text-emerald-950 shadow-sm' => $selected,
                            'border-emerald-200 bg-white/70 text-slate-600 hover:bg-white' => ! $selected,
                        ])>
                            <span class="flex items-center gap-3">
                            <x-ui.icon :name="$icon" class="h-4 w-4 text-emerald-800" />
                            {{ $label }}
                            </span>
                            @if ($selected)
                                <x-ui.icon name="check-circle" class="h-4 w-4 text-emerald-700" />
                            @else
                                <span class="h-4 w-4 rounded-full border border-slate-300"></span>
                            @endif
                        </button>
                    @endforeach
                </div>
                @error('reminderTypes') <p class="mt-1 text-sm text-red-700">{{ $message }}</p> @enderror
            </div>

            <div>
                <h2 class="text-lg font-black tracking-normal text-slate-950">Active days</h2>
                <div class="mt-3 grid grid-cols-4 gap-2 sm:grid-cols-7 lg:grid-cols-4">
                    @foreach ($weekdayOptions as $key => $label)
                        @php($selected = in_array($key, $days, true))
                        <button type="button" wire:click="toggleDay('{{ $key }}')" wire:key="day-{{ $key }}" @class([
                            'flex min-h-12 items-center justify-center rounded-xl border px-3 py-2 text-sm font-black transition',
                            'border-emerald-600 bg-emerald-700 text-white shadow-sm' => $selected,
                            'border-emerald-200 bg-white text-slate-700 hover:bg-emerald-50' => ! $selected,
                        ])>
                            {{ $label }}
                        </button>
                    @endforeach
                </div>
                @error('days') <p class="mt-1 text-sm text-red-700">{{ $message }}</p> @enderror
            </div>
        </div>

        <div class="mt-5 flex flex-col gap-3 sm:flex-row sm:items-center">
            <button type="submit" class="btn-primary w-full sm:w-auto"><x-ui.icon name="bell" class="h-4 w-4" /> Save reminder</button>
            <p class="text-sm text-slate-600">Email sends now. Push keeps in-app notification records ready for browser push subscription support.</p>
        </div>
    </form>
</div>
