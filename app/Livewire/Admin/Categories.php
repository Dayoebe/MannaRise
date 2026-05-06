<?php

namespace App\Livewire\Admin;

use App\Models\DevotionalCategory;
use App\Support\Toast;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Livewire\Component;

class Categories extends Component
{
    public ?int $editingId = null;

    public string $name = '';

    public string $slug = '';

    public string $description = '';

    public bool $is_active = true;

    public function save(): void
    {
        $this->slug = $this->slug !== '' ? Str::slug($this->slug) : Str::slug($this->name);

        $validated = $this->validate([
            'name' => ['required', 'string', 'max:255'],
            'slug' => ['required', 'string', 'max:255', Rule::unique('devotional_categories', 'slug')->ignore($this->editingId)],
            'description' => ['nullable', 'string'],
            'is_active' => ['boolean'],
        ]);

        if ($this->editingId) {
            DevotionalCategory::findOrFail($this->editingId)->update($validated);
        } else {
            DevotionalCategory::create($validated);
        }

        $this->resetForm();
        Toast::status($this, 'Category saved.');
    }

    public function edit(int $id): void
    {
        $category = DevotionalCategory::findOrFail($id);

        $this->editingId = $category->id;
        $this->name = $category->name;
        $this->slug = $category->slug;
        $this->description = $category->description ?? '';
        $this->is_active = $category->is_active;
    }

    public function delete(int $id): void
    {
        $category = DevotionalCategory::withCount('devotionals')->findOrFail($id);

        if ($category->devotionals_count > 0) {
            $this->addError('category', 'Move or delete devotionals in this category first.');

            return;
        }

        $category->delete();
        Toast::status($this, 'Category deleted.');
    }

    public function resetForm(): void
    {
        $this->editingId = null;
        $this->name = '';
        $this->slug = '';
        $this->description = '';
        $this->is_active = true;
        $this->resetErrorBag();
    }

    public function render()
    {
        return view('livewire.admin.categories', [
            'categories' => DevotionalCategory::withCount('devotionals')->orderBy('name')->get(),
        ]);
    }
}
