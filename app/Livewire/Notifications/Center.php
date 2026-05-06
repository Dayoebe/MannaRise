<?php

namespace App\Livewire\Notifications;

use Livewire\Component;

class Center extends Component
{
    public function markAsRead(string $notificationId): void
    {
        $notification = auth()->user()
            ?->notifications()
            ->whereKey($notificationId)
            ->first();

        $notification?->markAsRead();
    }

    public function markAllAsRead(): void
    {
        auth()->user()?->unreadNotifications()->update(['read_at' => now()]);
    }

    public function clearRead(): void
    {
        auth()->user()?->readNotifications()->delete();
    }

    public function render()
    {
        $user = auth()->user();

        return view('livewire.notifications.center', [
            'unreadCount' => $user?->unreadNotifications()->count() ?? 0,
            'notifications' => $user
                ? $user->notifications()->latest()->take(8)->get()
                : collect(),
        ]);
    }
}
