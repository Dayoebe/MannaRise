<?php

namespace App\Livewire\Admin;

use App\Models\PrayerRequest;
use Livewire\Component;
use Livewire\WithPagination;

class PrayerRequests extends Component
{
    use WithPagination;

    public string $search = '';

    public function updatedSearch(): void
    {
        $this->resetPage();
    }

    public function toggleAnswered(int $id): void
    {
        $request = PrayerRequest::findOrFail($id);
        $request->update(['is_answered' => ! $request->is_answered]);
    }

    public function delete(int $id): void
    {
        PrayerRequest::findOrFail($id)->delete();
        session()->flash('status', 'Prayer request deleted.');
    }

    public function render()
    {
        return view('livewire.admin.prayer-requests', [
            'requests' => PrayerRequest::query()
                ->with(['room', 'updates' => fn ($query) => $query->latest()])
                ->when($this->search !== '', fn ($query) => $query->where(function ($query) {
                    $query
                        ->where('title', 'like', "%{$this->search}%")
                        ->orWhere('name', 'like', "%{$this->search}%")
                        ->orWhere('email', 'like', "%{$this->search}%")
                        ->orWhereHas('room', fn ($roomQuery) => $roomQuery->where('name', 'like', "%{$this->search}%"));
                }))
                ->latest()
                ->paginate(12),
        ]);
    }
}
