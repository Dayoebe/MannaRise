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
                <h1 class="mt-3 app-section-title">User directory</h1>
                <p class="mt-2 max-w-2xl text-sm leading-6 text-slate-600">Open a user to review their information, activity, role assignments, admin access, and relevant work.</p>
            </div>
            <a href="{{ route('admin.roles') }}" class="btn-secondary w-full border-sky-200 text-sky-900 hover:bg-sky-50 sm:w-auto"><x-ui.icon name="shield" class="h-4 w-4" /> Manage roles</a>
        </div>
    </section>

    <section class="grid gap-6 xl:grid-cols-[minmax(18rem,24rem)_minmax(0,1fr)] xl:items-start">
        <aside class="app-panel border-sky-200 bg-sky-50 xl:sticky xl:top-28">
            <label class="block text-sm font-bold text-slate-700">Search users</label>
            <input type="search" wire:model.live.debounce.300ms="search" placeholder="Search by name or email" class="field-input mt-1 border-sky-300 focus:border-sky-600 focus:ring-sky-100">

            <div class="mt-4 space-y-2">
                @forelse ($users as $user)
                    <button type="button" wire:click="selectUser({{ $user->id }})" class="w-full rounded-xl border p-3 text-left transition {{ $selectedUserId === $user->id ? 'border-sky-600 bg-white shadow-sm ring-4 ring-sky-100' : 'border-white bg-white/80 hover:border-sky-200 hover:bg-white' }}">
                        <span class="flex items-start justify-between gap-3">
                            <span class="min-w-0">
                                <span class="block truncate font-black tracking-normal text-slate-950">{{ $user->name }}</span>
                                <span class="mt-1 block truncate text-sm font-bold text-slate-500">{{ $user->email }}</span>
                            </span>
                            @if ($user->is_super_admin)
                                <span class="rounded-full bg-indigo-50 px-2 py-1 text-[0.65rem] font-black uppercase tracking-normal text-indigo-900">Super</span>
                            @elseif ($user->hasAdminAccess())
                                <span class="rounded-full bg-emerald-50 px-2 py-1 text-[0.65rem] font-black uppercase tracking-normal text-emerald-900">Admin</span>
                            @endif
                        </span>
                        <span class="mt-2 flex flex-wrap gap-1">
                            @forelse ($user->roles->take(3) as $role)
                                <span class="rounded-full bg-sky-50 px-2 py-0.5 text-[0.65rem] font-bold uppercase tracking-normal text-sky-900">{{ $role->label }}</span>
                            @empty
                                <span class="rounded-full bg-slate-100 px-2 py-0.5 text-[0.65rem] font-bold uppercase tracking-normal text-slate-600">No role</span>
                            @endforelse
                        </span>
                    </button>
                @empty
                    <p class="rounded-xl border border-dashed border-sky-200 bg-white p-4 text-sm text-slate-600">No users found.</p>
                @endforelse
            </div>

            <div class="mt-4">{{ $users->links() }}</div>
        </aside>

        @if ($selectedUser)
            <section class="space-y-5">
                <article class="app-panel border-slate-200">
                    <div class="flex flex-col gap-4 lg:flex-row lg:items-start lg:justify-between">
                        <div class="min-w-0">
                            <div class="flex flex-wrap items-center gap-2">
                                @if ($selectedUser->is_super_admin)
                                    <span class="rounded-full bg-indigo-50 px-3 py-1 text-xs font-black uppercase tracking-normal text-indigo-900">Super admin</span>
                                @elseif ($selectedUser->hasAdminAccess())
                                    <span class="rounded-full bg-emerald-50 px-3 py-1 text-xs font-black uppercase tracking-normal text-emerald-900">Admin</span>
                                @else
                                    <span class="rounded-full bg-slate-100 px-3 py-1 text-xs font-black uppercase tracking-normal text-slate-700">Reader</span>
                                @endif
                                <span class="rounded-full bg-sky-50 px-3 py-1 text-xs font-black uppercase tracking-normal text-sky-900">Joined {{ $selectedUser->created_at->format('M j, Y') }}</span>
                            </div>
                            <h2 class="mt-3 break-words text-3xl font-black tracking-normal text-slate-950">{{ $selectedUser->name }}</h2>
                            <p class="mt-1 break-words text-sm font-bold text-slate-500">{{ $selectedUser->email }}</p>
                        </div>
                        <div class="grid gap-2 sm:grid-cols-2">
                            @unless ($selectedUser->hasAdminAccess())
                                <button type="button" wire:click="makeAdmin({{ $selectedUser->id }})" class="btn-primary bg-emerald-700 hover:bg-emerald-800">Make admin</button>
                            @else
                                <button type="button" wire:click="removeAdmin({{ $selectedUser->id }})" wire:confirm="Remove admin access for this user?" class="btn-secondary border-rose-200 text-rose-900 hover:bg-rose-50">Remove admin</button>
                            @endunless
                            <button type="button" wire:click="saveUserAccess({{ $selectedUser->id }})" class="btn-secondary border-sky-200 text-sky-900 hover:bg-sky-50">Save roles</button>
                        </div>
                    </div>
                </article>

                <section class="grid gap-4 md:grid-cols-4">
                    @foreach ([
                        ['Journal', $selectedStats['journals'], 'journal', 'border-sky-200 bg-sky-50 text-sky-900'],
                        ['Prayers', $selectedStats['prayers'], 'heart', 'border-rose-200 bg-rose-50 text-rose-900'],
                        ['Bible notes', $selectedStats['bible_notes'], 'bookmark', 'border-amber-200 bg-amber-50 text-amber-900'],
                        ['Testimonies', $selectedStats['testimonies'], 'message-circle', 'border-violet-200 bg-violet-50 text-violet-900'],
                    ] as [$label, $value, $icon, $classes])
                        <div class="metric-card {{ $classes }}">
                            <p class="flex items-center gap-2 text-sm font-bold"><x-ui.icon :name="$icon" class="h-4 w-4" /> {{ $label }}</p>
                            <p class="mt-2 text-3xl font-black tracking-normal text-slate-950">{{ $value }}</p>
                        </div>
                    @endforeach
                </section>

                <section class="grid gap-5 xl:grid-cols-[minmax(0,0.9fr)_minmax(0,1.1fr)]">
                    <form wire:submit="saveUserProfile" class="app-panel border-blue-200 bg-blue-50">
                        <p class="app-eyebrow border-blue-200 bg-white text-blue-900"><x-ui.icon name="users" class="h-4 w-4" /> Account information</p>
                        <div class="mt-5 space-y-4">
                            <div>
                                <label class="block text-sm font-bold text-slate-700">Name</label>
                                <input type="text" wire:model="name" class="field-input mt-1 border-blue-300 focus:border-blue-600 focus:ring-blue-100">
                                @error('name') <p class="mt-1 text-sm text-red-700">{{ $message }}</p> @enderror
                            </div>
                            <div>
                                <label class="block text-sm font-bold text-slate-700">Email</label>
                                <input type="email" wire:model="email" class="field-input mt-1 border-blue-300 focus:border-blue-600 focus:ring-blue-100">
                                @error('email') <p class="mt-1 text-sm text-red-700">{{ $message }}</p> @enderror
                            </div>
                            <label class="flex items-center gap-2 rounded-xl border border-blue-200 bg-white px-3 py-3 text-sm font-bold text-slate-700">
                                <input type="checkbox" wire:model="emailVerified" class="rounded border-blue-300 text-blue-700 focus:ring-blue-600"> Email verified
                            </label>
                            <div class="grid gap-3 sm:grid-cols-2">
                                <div>
                                    <label class="block text-sm font-bold text-slate-700">New password</label>
                                    <input type="password" wire:model="password" class="field-input mt-1 border-blue-300 focus:border-blue-600 focus:ring-blue-100">
                                    @error('password') <p class="mt-1 text-sm text-red-700">{{ $message }}</p> @enderror
                                </div>
                                <div>
                                    <label class="block text-sm font-bold text-slate-700">Confirm password</label>
                                    <input type="password" wire:model="password_confirmation" class="field-input mt-1 border-blue-300 focus:border-blue-600 focus:ring-blue-100">
                                </div>
                            </div>
                        </div>
                        <button type="submit" class="mt-5 btn-primary bg-blue-700 hover:bg-blue-800"><x-ui.icon name="check-circle" class="h-4 w-4" /> Save information</button>
                    </form>

                    <form wire:submit="saveUserAccess({{ $selectedUser->id }})" class="app-panel border-sky-200 bg-sky-50">
                        <p class="app-eyebrow border-sky-200 bg-white text-sky-900"><x-ui.icon name="shield" class="h-4 w-4" /> Roles and work</p>
                        <div class="mt-5 grid gap-2 sm:grid-cols-2">
                            <label class="flex items-center gap-2 rounded-xl border border-white bg-white px-3 py-3 text-sm font-bold text-slate-700">
                                <input type="checkbox" wire:model="adminFlags.{{ $selectedUser->id }}" class="rounded border-sky-300 text-sky-700 focus:ring-sky-600">
                                Admin access
                            </label>
                            <label class="flex items-center gap-2 rounded-xl border border-white bg-white px-3 py-3 text-sm font-bold text-slate-700">
                                <input type="checkbox" wire:model="superAdminFlags.{{ $selectedUser->id }}" class="rounded border-indigo-300 text-indigo-700 focus:ring-indigo-600">
                                Super admin
                            </label>
                        </div>

                        <div class="mt-4 grid gap-4 lg:grid-cols-2">
                            @foreach ($roleGroups as $group)
                                <div class="rounded-xl border border-white bg-white p-3">
                                    <p class="text-xs font-black uppercase tracking-[0.16em] text-slate-500">{{ $group['label'] }}</p>
                                    <div class="mt-2 grid gap-2">
                                        @foreach ($group['roles'] as $role)
                                            <label class="flex items-start gap-2 text-sm font-bold text-slate-700">
                                                <input type="checkbox" wire:model="roleSelections.{{ $selectedUser->id }}" value="{{ $role->id }}" class="mt-1 rounded border-sky-300 text-sky-700 focus:ring-sky-600">
                                                <span>
                                                    <span class="block">{{ $role->label }}</span>
                                                    <span class="mt-1 block text-xs font-semibold leading-5 text-slate-500">{{ $role->permissions->pluck('label')->join(', ') ?: 'No admin permissions' }}</span>
                                                </span>
                                            </label>
                                        @endforeach
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        <button type="submit" class="mt-5 btn-primary bg-sky-700 hover:bg-sky-800"><x-ui.icon name="check-circle" class="h-4 w-4" /> Save roles and permissions</button>
                    </form>
                </section>

                <section class="grid gap-5 lg:grid-cols-3">
                    <article class="app-panel border-emerald-200 bg-emerald-50">
                        <p class="app-eyebrow border-emerald-200 bg-white text-emerald-900"><x-ui.icon name="route" class="h-4 w-4" /> Spiritual profile</p>
                        <div class="mt-4 space-y-3 text-sm leading-6 text-slate-700">
                            <p><span class="font-black text-slate-950">Season:</span> {{ $selectedUser->spiritualProfile?->season ? str($selectedUser->spiritualProfile->season)->headline() : 'Not set' }}</p>
                            <p><span class="font-black text-slate-950">Goal:</span> {{ $selectedUser->spiritualProfile?->path_goal ?: 'Not set' }}</p>
                            <p><span class="font-black text-slate-950">Preferred time:</span> {{ $selectedUser->spiritualProfile?->preferred_time ? str($selectedUser->spiritualProfile->preferred_time)->headline() : 'Not set' }}</p>
                        </div>
                    </article>
                    <article class="app-panel border-amber-200 bg-amber-50">
                        <p class="app-eyebrow border-amber-200 bg-white text-amber-900"><x-ui.icon name="book-open" class="h-4 w-4" /> Recent activity</p>
                        <div class="mt-4 space-y-3 text-sm leading-6 text-slate-700">
                            <p><span class="font-black text-slate-950">Bible:</span> {{ $selectedStats['recent_bible'] }}</p>
                            <p><span class="font-black text-slate-950">Journal:</span> {{ $selectedStats['recent_journal'] }}</p>
                            <p><span class="font-black text-slate-950">Prayer:</span> {{ $selectedStats['recent_prayer'] }}</p>
                        </div>
                    </article>
                    <article class="app-panel border-indigo-200 bg-indigo-50">
                        <p class="app-eyebrow border-indigo-200 bg-white text-indigo-900"><x-ui.icon name="shield" class="h-4 w-4" /> Effective permissions</p>
                        <div class="mt-4 flex flex-wrap gap-2">
                            @forelse ($selectedUser->roles->flatMap->permissions->unique('id') as $permission)
                                <span class="rounded-full bg-white px-3 py-1 text-xs font-bold uppercase tracking-normal text-indigo-900">{{ $permission->label }}</span>
                            @empty
                                <span class="text-sm text-slate-600">No admin permissions assigned.</span>
                            @endforelse
                        </div>
                    </article>
                </section>
            </section>
        @else
            <section class="app-panel border-dashed border-slate-300 text-sm text-slate-600">Select a user to view and edit their information.</section>
        @endif
    </section>
</div>
