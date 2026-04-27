<?php

namespace App\Livewire\Admin;

use App\Models\Testimony;
use Livewire\Component;
use Livewire\WithPagination;

class Testimonies extends Component
{
    use WithPagination;

    public string $filter = 'pending';

    public function updatedFilter(): void
    {
        $this->resetPage();
    }

    public function toggleApproval(int $id): void
    {
        $testimony = Testimony::findOrFail($id);
        $testimony->update(['is_approved' => ! $testimony->is_approved]);
    }

    public function delete(int $id): void
    {
        Testimony::findOrFail($id)->delete();
        session()->flash('status', 'Testimony deleted.');
    }

    public function render()
    {
        return view('livewire.admin.testimonies', [
            'testimonies' => Testimony::query()
                ->when($this->filter === 'pending', fn ($query) => $query->where('is_approved', false))
                ->when($this->filter === 'approved', fn ($query) => $query->where('is_approved', true))
                ->latest()
                ->paginate(12),
        ]);
    }
}
