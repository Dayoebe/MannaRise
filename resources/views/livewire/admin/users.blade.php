<div class="space-y-6 sm:space-y-8">
    <section class="page-hero border-sky-200">
        <div class="color-strip rounded-none">
            <span class="bg-sky-500"></span>
            <span class="bg-blue-500"></span>
            <span class="bg-indigo-500"></span>
            <span class="bg-violet-500"></span>
            <span class="bg-emerald-500"></span>
        </div>
        <div class="page-hero-body">
            <div>
                <p class="app-eyebrow border-sky-200 bg-sky-50 text-sky-900"><x-ui.icon name="users" class="h-4 w-4" /> Users and admins</p>
                <h1 class="mt-3 app-section-title">Assign roles and relevant work</h1>
                <p class="mt-2 max-w-2xl text-sm leading-6 text-slate-600">Super admins can make admins, assign role-based work, and keep access aligned with each person&apos;s responsibility.</p>
            </div>
            <a href="{{ route('admin.roles') }}" class="btn-secondary w-full border-sky-200 text-sky-900 hover:bg-sky-50 sm:w-auto"><x-ui.icon name="shield" class="h-4 w-4" /> Manage roles</a>
        </div>
    </section>

    <section class="app-panel border-sky-200 bg-sky-50">
        <label class="block text-sm font-bold text-slate-700">Search users</label>
        <input type="search" wire:model.live.debounce.300ms="search" placeholder="Search by name or email" class="field-input mt-1 border-sky-300 focus:border-sky-600 focus:ring-sky-100">
    </section>

    <section class="grid gap-4">
        @forelse ($users as $user)
            <article class="app-panel border-slate-200">
                <div class="grid gap-5 xl:grid-cols-[minmax(0,1fr)_minmax(22rem,0.9fr)] xl:items-start">
                    <div>
                        <div class="flex flex-col gap-3 sm:flex-row sm:items-start sm:justify-between">
                            <div class="min-w-0">
                                <h2 class="break-words text-xl font-black tracking-normal text-slate-950">{{ $user->name }}</h2>
                                <p class="mt-1 break-words text-sm font-bold text-slate-500">{{ $user->email }}</p>
                            </div>
                            <div class="flex flex-wrap gap-2">
                                @if ($user->is_super_admin)
                                    <span class="rounded-full bg-indigo-50 px-3 py-1 text-xs font-black uppercase tracking-normal text-indigo-900">Super admin</span>
                                @elseif ($user->hasAdminAccess())
                                    <span class="rounded-full bg-emerald-50 px-3 py-1 text-xs font-black uppercase tracking-normal text-emerald-900">Admin</span>
                                @else
                                    <span class="rounded-full bg-slate-100 px-3 py-1 text-xs font-black uppercase tracking-normal text-slate-700">Reader</span>
                                @endif
                            </div>
                        </div>

                        <div class="mt-4 grid gap-3 sm:grid-cols-3">
                            <div class="rounded-xl border border-slate-100 bg-slate-50 p-3">
                                <p class="text-2xl font-black text-slate-950">{{ $user->journal_entries_count }}</p>
                                <p class="text-xs font-bold uppercase tracking-normal text-slate-500">Journal</p>
                            </div>
                            <div class="rounded-xl border border-slate-100 bg-slate-50 p-3">
                                <p class="text-2xl font-black text-slate-950">{{ $user->prayer_requests_count }}</p>
                                <p class="text-xs font-bold uppercase tracking-normal text-slate-500">Prayers</p>
                            </div>
                            <div class="rounded-xl border border-slate-100 bg-slate-50 p-3">
                                <p class="text-2xl font-black text-slate-950">{{ $user->testimonies_count }}</p>
                                <p class="text-xs font-bold uppercase tracking-normal text-slate-500">Testimonies</p>
                            </div>
                        </div>

                        <div class="mt-4 flex flex-wrap gap-2">
                            @foreach ($user->roles as $role)
                                <span class="rounded-full bg-sky-50 px-3 py-1 text-xs font-bold uppercase tracking-normal text-sky-900">{{ $role->label }}</span>
                            @endforeach
                        </div>
                    </div>

                    <form wire:submit="saveUserAccess({{ $user->id }})" class="rounded-2xl border border-sky-100 bg-sky-50 p-4">
                        <div class="grid gap-2 sm:grid-cols-2">
                            <label class="flex items-center gap-2 rounded-xl border border-white bg-white px-3 py-3 text-sm font-bold text-slate-700">
                                <input type="checkbox" wire:model="adminFlags.{{ $user->id }}" class="rounded border-sky-300 text-sky-700 focus:ring-sky-600">
                                Admin access
                            </label>
                            <label class="flex items-center gap-2 rounded-xl border border-white bg-white px-3 py-3 text-sm font-bold text-slate-700">
                                <input type="checkbox" wire:model="superAdminFlags.{{ $user->id }}" class="rounded border-indigo-300 text-indigo-700 focus:ring-indigo-600">
                                Super admin
                            </label>
                        </div>

                        <div class="mt-4 space-y-4">
                            @foreach ($roleGroups as $group)
                                <div>
                                    <p class="text-xs font-black uppercase tracking-[0.16em] text-slate-500">{{ $group['label'] }}</p>
                                    <div class="mt-2 grid gap-2">
                                        @foreach ($group['roles'] as $role)
                                            <label class="flex items-start gap-2 rounded-xl border border-white bg-white px-3 py-3 text-sm font-bold text-slate-700">
                                                <input type="checkbox" wire:model="roleSelections.{{ $user->id }}" value="{{ $role->id }}" class="mt-1 rounded border-sky-300 text-sky-700 focus:ring-sky-600">
                                                <span>
                                                    <span class="block">{{ $role->label }}</span>
                                                    <span class="mt-1 block text-xs font-semibold leading-5 text-slate-500">{{ $role->description ?: $role->permissions->pluck('label')->join(', ') }}</span>
                                                </span>
                                            </label>
                                        @endforeach
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        <div class="mt-4 flex flex-col gap-2 sm:flex-row">
                            <button type="submit" class="btn-primary w-full bg-sky-700 hover:bg-sky-800"><x-ui.icon name="check-circle" class="h-4 w-4" /> Save access</button>
                            @unless ($user->hasAdminAccess())
                                <button type="button" wire:click="makeAdmin({{ $user->id }})" class="btn-secondary w-full border-emerald-200 text-emerald-900 hover:bg-emerald-50">Make admin</button>
                            @else
                                <button type="button" wire:click="removeAdmin({{ $user->id }})" wire:confirm="Remove admin access for this user?" class="btn-secondary w-full border-rose-200 text-rose-900 hover:bg-rose-50">Remove admin</button>
                            @endunless
                        </div>
                    </form>
                </div>
            </article>
        @empty
            <div class="app-panel border-dashed border-slate-300 text-sm text-slate-600">No users found.</div>
        @endforelse
    </section>

    <div>{{ $users->links() }}</div>
</div>
