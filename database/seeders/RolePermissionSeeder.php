<?php

namespace Database\Seeders;

use App\Models\Permission;
use App\Models\Role;
use Illuminate\Database\Seeder;

class RolePermissionSeeder extends Seeder
{
    public function run(): void
    {
        $permissions = collect([
            ['manage-dashboard', 'Manage Dashboard', 'Admin'],
            ['manage-devotionals', 'Manage Devotionals', 'Content'],
            ['manage-categories', 'Manage Categories', 'Content'],
            ['manage-prayer-requests', 'Manage Prayer Requests', 'Moderation'],
            ['manage-testimonies', 'Manage Testimonies', 'Moderation'],
            ['view-engagement', 'View Engagement', 'Reports'],
            ['manage-users', 'Manage Users', 'People'],
            ['manage-roles', 'Manage Roles', 'Security'],
            ['manage-audio-devotionals', 'Manage Audio Devotionals', 'Content'],
            ['manage-notifications', 'Manage Notifications', 'Communication'],
        ])->mapWithKeys(function (array $permission) {
            [$name, $label, $group] = $permission;

            $model = Permission::updateOrCreate(
                ['name' => $name],
                [
                    'label' => $label,
                    'group' => $group,
                ],
            );

            return [$name => $model];
        });

        $roleMap = [
            'super-admin' => [
                'label' => 'Super Admin',
                'description' => 'Full system access across content, users, roles, and settings.',
                'permissions' => $permissions->keys()->all(),
            ],
            'admin' => [
                'label' => 'Admin',
                'description' => 'General administrative access for managing platform content and moderation.',
                'permissions' => ['manage-dashboard', 'manage-devotionals', 'manage-categories', 'manage-prayer-requests', 'manage-testimonies', 'view-engagement', 'manage-audio-devotionals', 'manage-notifications'],
            ],
            'editor' => [
                'label' => 'Editor',
                'description' => 'Creates and manages devotional content.',
                'permissions' => ['manage-dashboard', 'manage-devotionals', 'manage-categories', 'manage-audio-devotionals'],
            ],
            'moderator' => [
                'label' => 'Moderator',
                'description' => 'Moderates public prayer requests and testimonies.',
                'permissions' => ['manage-dashboard', 'manage-prayer-requests', 'manage-testimonies'],
            ],
            'reader' => [
                'label' => 'Reader',
                'description' => 'Default devotional reader role.',
                'permissions' => [],
            ],
        ];

        foreach ($roleMap as $name => $data) {
            $role = Role::updateOrCreate(
                ['name' => $name],
                [
                    'label' => $data['label'],
                    'description' => $data['description'],
                    'is_system' => true,
                ],
            );

            $role->permissions()->sync(
                collect($data['permissions'])->map(fn (string $permission) => $permissions[$permission]->id)->all()
            );
        }
    }
}
