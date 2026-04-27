<div class="space-y-6 sm:space-y-8">
    <div class="page-hero border-indigo-200">
        <div class="color-strip rounded-none"><span class="bg-indigo-500"></span><span class="bg-violet-500"></span><span class="bg-purple-500"></span><span class="bg-emerald-500"></span><span class="bg-amber-400"></span></div>
        <div class="p-5 sm:p-6">
            <p class="app-eyebrow border-indigo-200 bg-indigo-50 text-indigo-900"><x-ui.icon name="shield" class="h-4 w-4" /> Roles and permissions</p>
            <h1 class="mt-3 app-section-title">Manage access</h1>
            <p class="mt-2 text-sm text-slate-600">Create roles and control what each role can do across MannaRise.</p>
        </div>
    </div>

    <form wire:submit="save" class="app-panel border-indigo-200 bg-indigo-50">
        <div class="flex flex-col gap-3 md:flex-row md:items-center md:justify-between">
            <h2 class="text-xl font-black tracking-normal text-slate-950">{{ $editingId ? 'Edit role' : 'New role' }}</h2>
            @if ($editingId)<button type="button" wire:click="resetForm" class="btn-secondary border-slate-300 px-3">Cancel edit</button>@endif
        </div>

        <div class="mt-5 grid gap-4 md:grid-cols-2">
            <div>
                <label class="block text-sm font-bold text-slate-700">Role name</label>
                <input type="text" wire:model="name" class="field-input mt-1 border-indigo-300 focus:border-indigo-600 focus:ring-indigo-100" placeholder="content-manager">
                @error('name') <p class="mt-1 text-sm text-red-700">{{ $message }}</p> @enderror
            </div>
            <div>
                <label class="block text-sm font-bold text-slate-700">Label</label>
                <input type="text" wire:model="label" class="field-input mt-1 border-indigo-300 focus:border-indigo-600 focus:ring-indigo-100" placeholder="Content Manager">
                @error('label') <p class="mt-1 text-sm text-red-700">{{ $message }}</p> @enderror
            </div>
        </div>

        <div class="mt-4">
            <label class="block text-sm font-bold text-slate-700">Description</label>
            <textarea wire:model="description" rows="3" class="field-input mt-1 border-indigo-300 focus:border-indigo-600 focus:ring-indigo-100"></textarea>
            @error('description') <p class="mt-1 text-sm text-red-700">{{ $message }}</p> @enderror
        </div>

        <div class="mt-5 grid gap-4 lg:grid-cols-2">
            @foreach ($permissionsByGroup as $group => $permissions)
                <div class="rounded-2xl border border-indigo-100 bg-white p-4">
                    <h3 class="font-black tracking-normal text-slate-950">{{ $group }}</h3>
                    <div class="mt-3 grid gap-2">
                        @foreach ($permissions as $permission)
                            <label class="flex items-center gap-2 text-sm font-bold text-slate-700">
                                <input type="checkbox" wire:model="selectedPermissions" value="{{ $permission->id }}" class="rounded border-indigo-300 text-indigo-700 focus:ring-indigo-600">
                                {{ $permission->label }}
                            </label>
                        @endforeach
                    </div>
                </div>
            @endforeach
        </div>

        <button type="submit" class="mt-5 btn-primary bg-indigo-700 hover:bg-indigo-800">Save role</button>
    </form>

    <section>
        <h2 class="mb-4 text-2xl font-black tracking-normal text-slate-950">Existing roles</h2>
        <div class="table-shell border-indigo-200">
            <table class="min-w-full divide-y divide-indigo-100 text-sm">
                <thead class="bg-indigo-50 text-left text-indigo-900"><tr><th class="px-4 py-3 font-black">Role</th><th class="px-4 py-3 font-black">Users</th><th class="px-4 py-3 font-black">Permissions</th><th class="px-4 py-3 font-black"></th></tr></thead>
                <tbody class="divide-y divide-indigo-100">
                    @forelse ($roles as $role)
                        <tr>
                            <td class="px-4 py-3"><p class="font-black tracking-normal text-slate-950">{{ $role->label }}</p><p class="text-slate-500">{{ $role->name }} @if($role->is_system) · system @endif</p></td>
                            <td class="px-4 py-3 font-bold text-slate-700">{{ $role->users_count }}</td>
                            <td class="px-4 py-3 font-bold text-slate-700">{{ $role->permissions_count }}</td>
                            <td class="px-4 py-3"><div class="flex justify-end gap-2"><button type="button" wire:click="edit({{ $role->id }})" class="rounded-full border border-slate-300 px-3 py-1.5 font-bold text-slate-800 hover:bg-slate-50">Edit</button><button type="button" wire:click="delete({{ $role->id }})" wire:confirm="Delete this role?" class="rounded-full border border-red-200 px-3 py-1.5 font-bold text-red-700 hover:bg-red-50">Delete</button></div></td>
                        </tr>
                    @empty
                        <tr><td colspan="4" class="px-4 py-6"><x-ui.empty-state title="No roles yet" message="Default roles will appear after running migrations." /></td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="mt-5">{{ $roles->links() }}</div>
    </section>
</div>
