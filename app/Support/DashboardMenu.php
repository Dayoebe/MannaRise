<?php

namespace App\Support;

use App\Models\User;
use Illuminate\Support\Facades\Route;

class DashboardMenu
{
    public static function forUser(?User $user): array
    {
        return collect(config('dashboard_menu.groups', []))
            ->map(fn (array $group) => self::groupForUser($group, $user))
            ->filter()
            ->values()
            ->all();
    }

    private static function groupForUser(array $group, ?User $user): ?array
    {
        if (($group['requires_admin'] ?? false) && ! $user?->hasAdminAccess()) {
            return null;
        }

        $items = collect($group['items'] ?? [])
            ->map(fn (array $item) => self::itemForUser($item, $user))
            ->filter()
            ->values()
            ->all();

        if ($items === []) {
            return null;
        }

        return [
            'label' => $group['label'],
            'items' => $items,
        ];
    }

    private static function itemForUser(array $item, ?User $user): ?array
    {
        if (! Route::has($item['route'])) {
            return null;
        }

        if (($item['requires_admin'] ?? false) && ! $user?->hasAdminAccess()) {
            return null;
        }

        if (($item['ability'] ?? null) && ! $user?->canDo($item['ability'])) {
            return null;
        }

        $active = $item['active'] ?? [$item['route']];

        return [
            'label' => $item['label'],
            'route' => $item['route'],
            'url' => route($item['route']),
            'icon' => $item['icon'] ?? 'circle',
            'active' => $active,
            'is_active' => request()->routeIs(...$active),
        ];
    }
}
