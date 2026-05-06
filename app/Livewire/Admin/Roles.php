<?php

namespace App\Livewire\Admin;

use App\Models\Permission;
use App\Models\Role;
use App\Support\Toast;
use Illuminate\Validation\Rule;
use Livewire\Component;
use Livewire\WithPagination;

class Roles extends Component
{
    use WithPagination;

    public ?int $editingId = null;

    public string $name = '';

    public string $label = '';

    public string $description = '';

    public array $selectedPermissions = [];

    public function save(): void
    {
        abort_unless(auth()->user()?->canDo('manage-roles'), 403);

        $validated = $this->validate([
            'name' => ['required', 'string', 'max:255', Rule::unique('roles', 'name')->ignore($this->editingId)],
            'label' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'selectedPermissions' => ['array'],
            'selectedPermissions.*' => ['exists:permissions,id'],
        ]);

        $role = $this->editingId
            ? Role::findOrFail($this->editingId)
            : new Role;

        $role->fill([
            'name' => str($validated['name'])->slug()->toString(),
            'label' => $validated['label'],
            'description' => $validated['description'] ?? null,
            'is_system' => $role->exists ? $role->is_system : false,
        ])->save();

        $role->permissions()->sync($validated['selectedPermissions'] ?? []);

        $this->resetForm();
        Toast::status($this, 'Role saved.');
    }

    public function edit(int $id): void
    {
        abort_unless(auth()->user()?->canDo('manage-roles'), 403);

        $role = Role::with('permissions')->findOrFail($id);
        $this->editingId = $role->id;
        $this->name = $role->name;
        $this->label = $role->label;
        $this->description = $role->description ?? '';
        $this->selectedPermissions = $role->permissions->pluck('id')->map(fn ($id) => (string) $id)->all();
    }

    public function delete(int $id): void
    {
        abort_unless(auth()->user()?->canDo('manage-roles'), 403);

        $role = Role::findOrFail($id);

        if ($role->is_system) {
            Toast::status($this, 'System roles cannot be deleted.');

            return;
        }

        $role->delete();
        Toast::status($this, 'Role deleted.');
    }

    public function resetForm(): void
    {
        $this->editingId = null;
        $this->name = '';
        $this->label = '';
        $this->description = '';
        $this->selectedPermissions = [];
        $this->resetErrorBag();
    }

    public function render()
    {
        return view('livewire.admin.roles', [
            'roles' => Role::withCount(['users', 'permissions'])->orderBy('label')->paginate(10),
            'permissionsByGroup' => Permission::orderBy('group')->orderBy('label')->get()->groupBy('group'),
        ]);
    }
}
