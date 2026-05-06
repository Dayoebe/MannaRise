<?php

namespace App\Livewire\Admin;

use App\Models\Testimony;
use App\Support\Toast;
use Livewire\Component;
use Livewire\WithPagination;

class Testimonies extends Component
{
    use WithPagination;

    public string $filter = Testimony::STATUS_PENDING;

    public function updatedFilter(): void
    {
        $this->resetPage();
    }

    public function toggleApproval(int $id): void
    {
        $testimony = Testimony::findOrFail($id);

        if ($testimony->moderation_status === Testimony::STATUS_APPROVED) {
            $testimony->markPending(auth()->id());
            Toast::status($this, 'Testimony moved back to pending review.');

            return;
        }

        $testimony->approve(auth()->id());
        Toast::status($this, 'Testimony approved.');
    }

    public function delete(int $id): void
    {
        Testimony::findOrFail($id)->delete();
        Toast::status($this, 'Testimony deleted.');
    }

    public function render()
    {
        return view('livewire.admin.testimonies', [
            'testimonies' => Testimony::query()
                ->when($this->filter === Testimony::STATUS_PENDING, fn ($query) => $query->pending())
                ->when($this->filter === Testimony::STATUS_APPROVED, fn ($query) => $query->approved())
                ->when($this->filter === Testimony::STATUS_REJECTED, fn ($query) => $query->rejected())
                ->latest()
                ->paginate(12),
        ]);
    }
}
