<div class="space-y-6 sm:space-y-8">
    <section class="page-hero border-emerald-200">
        <div class="color-strip rounded-none">
            <span class="bg-emerald-500"></span>
            <span class="bg-sky-500"></span>
            <span class="bg-violet-500"></span>
            <span class="bg-amber-400"></span>
        </div>
        <div class="page-hero-body">
            <div>
                <p class="app-eyebrow"><x-ui.icon name="route" class="h-4 w-4" /> Start well</p>
                <h1 class="mt-3 app-section-title">Set your first MannaRise path</h1>
                <p class="mt-2 max-w-2xl text-sm leading-6 text-slate-600">Choose what you are walking through, set a gentle rhythm, and begin with a plan that fits your season.</p>
            </div>
            <button type="button" wire:click="skip" class="btn-secondary w-full sm:w-auto">Skip for now</button>
        </div>
    </section>

    <form wire:submit="finish" class="grid gap-5 lg:grid-cols-[minmax(0,1fr)_minmax(18rem,26rem)]">
        <section class="app-panel border-violet-200 bg-violet-50">
            <p class="app-eyebrow border-violet-200 bg-white text-violet-900"><x-ui.icon name="heart" class="h-4 w-4" /> Your season</p>
            <div class="mt-5 grid gap-3 sm:grid-cols-2">
                @foreach ($seasons as $key => $option)
                    <label class="cursor-pointer rounded-xl border bg-white p-4 shadow-sm transition {{ $season === $key ? 'border-violet-500 ring-4 ring-violet-100' : 'border-white hover:border-violet-200' }}">
                        <input type="radio" wire:model="season" value="{{ $key }}" class="sr-only">
                        <span class="block font-black tracking-normal text-slate-950">{{ $option['label'] }}</span>
                        <span class="mt-2 block text-sm leading-6 text-slate-600">{{ $option['reference'] }} · {{ $option['action'] }}</span>
                    </label>
                @endforeach
            </div>
            @error('season') <p class="mt-2 text-sm text-red-700">{{ $message }}</p> @enderror
        </section>

        <aside class="space-y-5">
            <section class="app-panel border-sky-200 bg-sky-50">
                <p class="app-eyebrow border-sky-200 bg-white text-sky-900"><x-ui.icon name="bell" class="h-4 w-4" /> Rhythm</p>
                <div class="mt-4 space-y-4">
                    <div>
                        <label class="block text-sm font-bold text-slate-700">Path goal</label>
                        <input type="text" wire:model="path_goal" placeholder="What are you asking God to grow?" class="field-input mt-1 border-sky-300 focus:border-sky-600 focus:ring-sky-100">
                    </div>
                    <div>
                        <label class="block text-sm font-bold text-slate-700">Best time</label>
                        <select wire:model="preferred_time" class="field-input mt-1 border-sky-300 focus:border-sky-600 focus:ring-sky-100">
                            <option value="morning">Morning</option>
                            <option value="midday">Midday</option>
                            <option value="evening">Evening</option>
                        </select>
                    </div>
                    <label class="flex items-center gap-2 rounded-xl border border-sky-200 bg-white px-3 py-3 text-sm font-bold text-slate-700">
                        <input type="checkbox" wire:model="daily_reminder" class="rounded border-sky-300 text-sky-700 focus:ring-sky-600"> Daily reminder
                    </label>
                    <label class="flex items-center gap-2 rounded-xl border border-sky-200 bg-white px-3 py-3 text-sm font-bold text-slate-700">
                        <input type="checkbox" wire:model="memory_reminder" class="rounded border-sky-300 text-sky-700 focus:ring-sky-600"> Memory verse reminder marker
                    </label>
                </div>
            </section>

            <section class="app-panel border-amber-200 bg-amber-50">
                <p class="app-eyebrow border-amber-200 bg-white text-amber-900"><x-ui.icon name="route" class="h-4 w-4" /> First plan</p>
                <select wire:model="first_plan" class="field-input mt-4 border-amber-300 focus:border-amber-600 focus:ring-amber-100">
                    @foreach ($plans as $slug => $plan)
                        <option value="{{ $slug }}">{{ $plan['title'] }}</option>
                    @endforeach
                </select>
                @error('first_plan') <p class="mt-2 text-sm text-red-700">{{ $message }}</p> @enderror
                <button type="submit" class="mt-4 btn-primary w-full"><x-ui.icon name="check-circle" class="h-4 w-4" /> Start my path</button>
            </section>
        </aside>
    </form>
</div>
