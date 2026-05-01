<?php

namespace App\Livewire\Admin;

use App\Models\Role;
use App\Models\User;
use Illuminate\Support\Collection;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;
use Livewire\Component;
use Livewire\WithPagination;

class Users extends Component
{
    use WithPagination;

    public string $search = '';

    public ?int $selectedUserId = null;

    public string $name = '';

    public string $email = '';

    public string $password = '';

    public string $password_confirmation = '';

    public bool $emailVerified = false;

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

    public function selectUser(int $userId): void
    {
        abort_unless(auth()->user()?->canDo('manage-users'), 403);

        $user = User::with(['roles.permissions', 'spiritualProfile', 'dailyRhythmCheckIns', 'devotionalCompletions', 'journalEntries', 'prayerRequests', 'testimonies'])->findOrFail($userId);

        $this->selectedUserId = $user->id;
        $this->name = $user->name;
        $this->email = $user->email;
        $this->password = '';
        $this->password_confirmation = '';
        $this->emailVerified = (bool) $user->email_verified_at;
        $this->syncUserState($user, true);
    }

    public function saveUserProfile(): void
    {
        abort_unless(auth()->user()?->canDo('manage-users'), 403);
        abort_unless($this->selectedUserId, 404);

        $validated = $this->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', Rule::unique('users', 'email')->ignore($this->selectedUserId)],
            'password' => ['nullable', 'confirmed', Password::defaults()],
            'emailVerified' => ['boolean'],
        ]);

        $user = User::findOrFail($this->selectedUserId);
        $payload = [
            'name' => $validated['name'],
            'email' => $validated['email'],
            'email_verified_at' => $validated['emailVerified'] ? ($user->email_verified_at ?: now()) : null,
        ];

        if ($validated['password'] !== '') {
            $payload['password'] = $validated['password'];
        }

        $user->update($payload);

        $this->password = '';
        $this->password_confirmation = '';
        $this->selectUser($user->id);

        session()->flash('status', 'User information updated.');
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
        $this->refreshSelectedUser($user->id);

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
        $this->refreshSelectedUser($user->id);

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
        $this->refreshSelectedUser($user->id);

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

        if (! $this->selectedUserId && $users->isNotEmpty()) {
            $this->selectUser($users->first()->id);
        }

        $selectedUser = $this->selectedUserId
            ? User::with([
                'roles.permissions',
                'spiritualProfile',
                'favoriteDevotionals',
                'devotionalCompletions',
                'journalEntries',
                'prayerRequests',
                'testimonies',
                'bibleVerseEngagements.verse.book',
                'bibleReadingHistories.book',
                'dailyRhythmCheckIns',
            ])->withCount([
                'favoriteDevotionals as favorites_count',
                'devotionalCompletions as completions_count',
                'journalEntries',
                'prayerRequests',
                'testimonies',
                'bibleVerseEngagements as bible_notes_count',
                'bibleReadingHistories as bible_reading_count',
            ])->find($this->selectedUserId)
            : null;

        return view('livewire.admin.users', [
            'users' => $users,
            'selectedUser' => $selectedUser,
            'selectedStats' => $selectedUser ? $this->selectedStats($selectedUser) : null,
            'roles' => Role::with('permissions')->orderByDesc('is_system')->orderBy('label')->get(),
            'roleGroups' => $this->roleGroups(),
        ]);
    }

    private function refreshSelectedUser(int $userId): void
    {
        if ($this->selectedUserId === $userId) {
            $this->selectUser($userId);
        }
    }

    /**
     * @return array<string, mixed>
     */
    private function selectedStats(User $user): array
    {
        $recentBible = $user->bibleReadingHistories->sortByDesc('last_read_at')->first();
        $recentJournal = $user->journalEntries->sortByDesc('entry_date')->first();
        $recentPrayer = $user->prayerRequests->sortByDesc('created_at')->first();

        return [
            'favorites' => (int) ($user->favorites_count ?? 0),
            'completions' => (int) ($user->completions_count ?? 0),
            'journals' => (int) ($user->journal_entries_count ?? 0),
            'prayers' => (int) ($user->prayer_requests_count ?? 0),
            'testimonies' => (int) ($user->testimonies_count ?? 0),
            'bible_notes' => (int) ($user->bible_notes_count ?? 0),
            'bible_reading' => (int) ($user->bible_reading_count ?? 0),
            'recent_bible' => $recentBible ? "{$recentBible->book?->name} {$recentBible->chapter}" : 'No Bible reading yet',
            'recent_journal' => $recentJournal?->title ?: 'No journal entry yet',
            'recent_prayer' => $recentPrayer?->title ?: 'No prayer request yet',
        ];
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
