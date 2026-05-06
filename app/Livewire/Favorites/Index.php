<?php

namespace App\Livewire\Favorites;

use App\Models\Devotional;
use App\Models\DevotionalFavorite;
use App\Support\Toast;
use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination;

    public function remove(int $devotionalId): void
    {
        DevotionalFavorite::where('user_id', auth()->id())
            ->where('devotional_id', $devotionalId)
            ->delete();

        Toast::status($this, 'Favorite removed.');
    }

    public function render()
    {
        return view('livewire.favorites.index', [
            'devotionals' => Devotional::with('category')
                ->whereHas('favoritedBy', fn ($query) => $query->where('users.id', auth()->id()))
                ->latest()
                ->paginate(9),
        ]);
    }
}
