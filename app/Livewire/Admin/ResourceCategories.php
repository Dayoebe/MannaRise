<?php

namespace App\Livewire\Admin;

use App\Models\ResourceCategory;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Livewire\Component;
use Livewire\WithPagination;

class ResourceCategories extends Component
{
    use WithPagination;

    public ?int $editingId = null;
    public string $name = '';
    public string $slug = '';
    public string $description = '';
    public string $icon = 'library';
    public string $type = '';
    public bool $is_active = true;

    public function save(): void
    {
        $this->slug = $this->slug !== '' ? Str::slug($this->slug) : Str::slug($this->name);

        $validated = $this->validate([
            'name' => ['required', 'string', 'max:255'],
            'slug' => ['required', 'string', 'max:255', Rule::unique('resource_categories', 'slug')->ignore($this->editingId)],
            'description' => ['nullable', 'string'],
            'icon' => ['nullable', 'string', 'max:80'],
            'type' => ['nullable', 'string', 'max:40'],
            'is_active' => ['boolean'],
        ]);

        ResourceCategory::updateOrCreate(['id' => $this->editingId], $validated);
        $this->resetForm();
        session()->flash('status', 'Resource category saved.');
    }

    public function edit(int $id): void
    {
        $category = ResourceCategory::findOrFail($id);
        $this->editingId = $category->id;
        $this->name = $category->name;
        $this->slug = $category->slug;
        $this->description = $category->description ?? '';
        $this->icon = $category->icon ?? 'library';
        $this->type = $category->type ?? '';
        $this->is_active = $category->is_active;
    }

    public function delete(int $id): void
    {
        ResourceCategory::findOrFail($id)->delete();
        session()->flash('status', 'Resource category deleted.');
    }

    public function resetForm(): void
    {
        $this->reset(['editingId', 'name', 'slug', 'description', 'type']);
        $this->icon = 'library';
        $this->is_active = true;
        $this->resetErrorBag();
    }

    public function render()
    {
        return view('livewire.admin.resource-categories', [
            'categories' => ResourceCategory::withCount('items')->orderBy('name')->paginate(12),
        ]);
    }
}
