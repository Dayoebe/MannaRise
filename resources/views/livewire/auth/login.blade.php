<div class="mx-auto max-w-md">
    <div class="rounded-lg border border-stone-200 bg-white p-6 shadow-sm">
        <div class="mb-6">
            <h1 class="text-2xl font-semibold text-stone-950">Log in</h1>
            <p class="mt-2 text-sm text-stone-600">Continue your devotional rhythm.</p>
        </div>

        <form wire:submit="login" class="space-y-5">
            <div>
                <label for="email" class="block text-sm font-medium text-stone-700">Email</label>
                <input id="email" type="email" wire:model="email" autocomplete="email" class="mt-1 w-full rounded-md border border-stone-300 bg-white px-3 py-2 text-sm outline-none focus:border-emerald-600 focus:ring-2 focus:ring-emerald-100">
                @error('email') <p class="mt-1 text-sm text-red-700">{{ $message }}</p> @enderror
            </div>

            <div>
                <label for="password" class="block text-sm font-medium text-stone-700">Password</label>
                <input id="password" type="password" wire:model="password" autocomplete="current-password" class="mt-1 w-full rounded-md border border-stone-300 bg-white px-3 py-2 text-sm outline-none focus:border-emerald-600 focus:ring-2 focus:ring-emerald-100">
                @error('password') <p class="mt-1 text-sm text-red-700">{{ $message }}</p> @enderror
            </div>

            <label class="flex items-center gap-2 text-sm text-stone-700">
                <input type="checkbox" wire:model="remember" class="rounded border-stone-300 text-emerald-700 focus:ring-emerald-600">
                Remember me
            </label>

            <button type="submit" class="w-full rounded-md bg-emerald-700 px-4 py-2.5 text-sm font-semibold text-white hover:bg-emerald-800 disabled:opacity-60" wire:loading.attr="disabled">
                Log in
            </button>
        </form>

        <p class="mt-6 text-center text-sm text-stone-600">
            New to MannaRise?
            <a href="{{ route('register') }}" class="font-medium text-emerald-800 hover:underline">Create an account</a>
        </p>
    </div>
</div>
