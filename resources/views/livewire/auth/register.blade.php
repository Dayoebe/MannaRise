<div class="mx-auto max-w-md">
    <div class="app-panel overflow-hidden border-violet-200 p-0 sm:p-0">
        <div class="color-strip rounded-none">
            <span class="bg-violet-500"></span>
            <span class="bg-purple-500"></span>
            <span class="bg-fuchsia-500"></span>
            <span class="bg-pink-500"></span>
            <span class="bg-emerald-500"></span>
        </div>
        <div class="p-5 sm:p-6">
            <div class="mb-6">
                <p class="app-eyebrow border-violet-200 bg-violet-50 text-violet-900"><x-ui.icon name="sparkles" class="h-4 w-4" /> Join</p>
                <h1 class="mt-3 text-2xl font-black tracking-normal text-slate-950">Create account</h1>
                <p class="mt-2 text-sm text-slate-600">Save devotionals, journal reflections, and track your reading.</p>
            </div>

            <form wire:submit="register" class="space-y-5">
                <div>
                    <label for="name" class="block text-sm font-bold text-slate-700">Name</label>
                    <input id="name" type="text" wire:model="name" autocomplete="name" class="field-input mt-1 border-violet-300 focus:border-violet-600 focus:ring-violet-100">
                    @error('name') <p class="mt-1 text-sm text-red-700">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label for="email" class="block text-sm font-bold text-slate-700">Email</label>
                    <input id="email" type="email" wire:model="email" autocomplete="email" class="field-input mt-1 border-violet-300 focus:border-violet-600 focus:ring-violet-100">
                    @error('email') <p class="mt-1 text-sm text-red-700">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label for="password" class="block text-sm font-bold text-slate-700">Password</label>
                    <input id="password" type="password" wire:model="password" autocomplete="new-password" class="field-input mt-1 border-violet-300 focus:border-violet-600 focus:ring-violet-100">
                    @error('password') <p class="mt-1 text-sm text-red-700">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label for="password_confirmation" class="block text-sm font-bold text-slate-700">Confirm password</label>
                    <input id="password_confirmation" type="password" wire:model="password_confirmation" autocomplete="new-password" class="field-input mt-1 border-violet-300 focus:border-violet-600 focus:ring-violet-100">
                </div>

                <button type="submit" class="btn-primary w-full bg-violet-700 hover:bg-violet-800" wire:loading.attr="disabled">
                    <x-ui.icon name="sparkles" class="h-4 w-4" /> Create account
</button>
            </form>

            <p class="mt-6 text-center text-sm text-slate-600">
                Already have an account?
                <a href="{{ route('login') }}" class="font-bold text-violet-800 hover:underline">Log in</a>
</p>
        </div>
    </div>
</div>
