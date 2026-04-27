<div class="mx-auto max-w-md">
    <div class="app-panel overflow-hidden border-emerald-200 p-0 sm:p-0">
        <div class="color-strip rounded-none">
            <span class="bg-emerald-500"></span>
            <span class="bg-teal-500"></span>
            <span class="bg-sky-500"></span>
            <span class="bg-amber-400"></span>
        </div>
        <div class="p-5 sm:p-6">
            <div class="mb-6">
                <p class="app-eyebrow"><x-ui.icon name="log-in" class="h-4 w-4" /> 🌿 Welcome</p>
                <h1 class="mt-3 text-2xl font-black tracking-normal text-slate-950">Log in</h1>
                <p class="mt-2 text-sm text-slate-600">Continue your devotional rhythm.</p>
            </div>

            <form wire:submit="login" class="space-y-5">
                <div>
                    <label for="email" class="block text-sm font-bold text-slate-700">Email</label>
                    <input id="email" type="email" wire:model="email" autocomplete="email" class="field-input mt-1">
                    @error('email') <p class="mt-1 text-sm text-red-700">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label for="password" class="block text-sm font-bold text-slate-700">Password</label>
                    <input id="password" type="password" wire:model="password" autocomplete="current-password" class="field-input mt-1">
                    @error('password') <p class="mt-1 text-sm text-red-700">{{ $message }}</p> @enderror
                </div>

                <label class="flex items-center gap-2 rounded-xl border border-emerald-100 bg-emerald-50 px-3 py-3 text-sm font-bold text-slate-700">
                    <input type="checkbox" wire:model="remember" class="rounded border-emerald-300 text-emerald-700 focus:ring-emerald-600">
                    Remember me
                </label>

                <button type="submit" class="btn-primary w-full" wire:loading.attr="disabled">
                    <x-ui.icon name="log-in" class="h-4 w-4" /> Log in
                </button>
            </form>

            <p class="mt-6 text-center text-sm text-slate-600">
                New to MannaRise?
                <a href="{{ route('register') }}" class="font-bold text-emerald-800 hover:underline">Create an account</a>
            </p>
        </div>
    </div>
</div>
