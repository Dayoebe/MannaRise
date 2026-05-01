<?php

namespace App\Livewire\Admin;

use App\Models\Role;
use App\Models\User;
use Illuminate\Support\Collection;
use Livewire\Component;
use Livewire\WithPagination;

class Users extends Component
{
    use WithPagination;

    public string $search = '';

    /**
     * @var array<int, array<int, string>>
     */
    public array $roleSelections = [];

    /**
     * @var array<int, bool>
     */
    public array $adminFlags = [];

    /**
     * @var array<int, bool>
     */
    public array $superAdminFlags = [];

    public function updatedSearch(): void
    {
        $this->resetPage();
    }

    public function saveUserAccess(int $userId): void
    {
        abort_unless(auth()->user()?->canDo('manage-users'), 403);

        $user = User::with('roles')->findOrFail($userId);
        $selectedRoleIds = collect($this->roleSelections[$userId] ?? [])
            ->map(fn ($id) => (int) $id)
            ->filter()
            ->unique()
            ->values();

        $roles = Role::with('permissions')->whereIn('id', $selectedRoleIds)->get();
        $superAdminRole = Role::where('name', 'super-admin')->first();
        $isSuperAdmin = (bool) ($this->superAdminFlags[$userId] ?? false)
            || $roles->contains('name', 'super-admin');

        if ($user->is(auth()->user()) && ! $isSuperAdmin && auth()->user()->is_super_admin) {
            session()->flash('status', 'You cannot remove your own super admin access.');
            $this->syncUserState($user->fresh('roles'), true);
            return;
        }

        if ($isSuperAdmin && $superAdminRole && ! $selectedRoleIds->contains($superAdminRole->id)) {
            $selectedRoleIds->push($superAdminRole->id);
            $roles->push($superAdminRole->load('permissions'));
        }

        $hasAdminWork = $roles->contains(fn (Role $role) => $role->permissions->isNotEmpty());
        $isAdmin = $isSuperAdmin || (bool) ($this->adminFlags[$userId] ?? false) || $hasAdminWork;

        $user->roles()->sync($selectedRoleIds->all());
        $user->forceFill([
            'is_admin' => $isAdmin,
            'is_super_admin' => $isSuperAdmin,
        ])->save();

        $this->syncUserState($user->fresh('roles.permissions'), true);

        session()->flash('status', "{$user->name}'s access was updated.");
    }

    public function makeAdmin(int $userId): void
    {
        abort_unless(auth()->user()?->canDo('manage-users'), 403);

        $user = User::findOrFail($userId);
        $adminRole = Role::where('name', 'admin')->first();

        if ($adminRole) {
            $user->roles()->syncWithoutDetaching([$adminRole->id]);
        }

        $user->forceFill(['is_admin' => true])->save();
        $this->syncUserState($user->fresh('roles.permissions'), true);

        session()->flash('status', "{$user->name} is now an admin.");
    }

    public function removeAdmin(int $userId): void
    {
        abort_unless(auth()->user()?->canDo('manage-users'), 403);

        $user = User::with('roles')->findOrFail($userId);

        if ($user->is(auth()->user())) {
            session()->flash('status', 'You cannot remove your own admin access here.');
            return;
        }

        $adminRoleIds = Role::whereHas('permissions')->pluck('id');
        $user->roles()->detach($adminRoleIds);
        $user->forceFill(['is_admin' => false, 'is_super_admin' => false])->save();
        $this->syncUserState($user->fresh('roles.permissions'), true);

        session()->flash('status', "{$user->name}'s admin access was removed.");
    }

    public function render()
    {
        $users = User::query()
            ->with(['roles.permissions'])
            ->withCount(['journalEntries', 'prayerRequests', 'testimonies'])
            ->when($this->search !== '', fn ($query) => $query
                ->where('name', 'like', "%{$this->search}%")
                ->orWhere('email', 'like', "%{$this->search}%"))
            ->orderByDesc('is_super_admin')
            ->orderByDesc('is_admin')
            ->orderBy('name')
            ->paginate(12);

        $users->getCollection()->each(fn (User $user) => $this->syncUserState($user));

        return view('livewire.admin.users', [
            'users' => $users,
            'roles' => Role::with('permissions')->orderByDesc('is_system')->orderBy('label')->get(),
            'roleGroups' => $this->roleGroups(),
        ]);
    }

    private function syncUserState(User $user, bool $force = false): void
    {
        if (! $force && array_key_exists($user->id, $this->roleSelections)) {
            return;
        }

        $this->roleSelections[$user->id] = $user->roles->pluck('id')->map(fn ($id) => (string) $id)->all();
        $this->adminFlags[$user->id] = (bool) $user->is_admin;
        $this->superAdminFlags[$user->id] = (bool) $user->is_super_admin;
    }

    /**
     * @return Collection<int, array{label: string, roles: Collection<int, Role>}>
     */
    private function roleGroups(): Collection
    {
        return Role::with('permissions')->orderBy('label')->get()
            ->groupBy(fn (Role $role) => $role->permissions->pluck('group')->first() ?: 'General')
            ->map(fn (Collection $roles, string $group) => ['label' => $group, 'roles' => $roles])
            ->values();
    }
}
