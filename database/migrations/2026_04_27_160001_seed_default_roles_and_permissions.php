<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        $now = now();

        $permissions = [
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
        ];

        foreach ($permissions as [$name, $label, $group]) {
            DB::table('permissions')->updateOrInsert(
                ['name' => $name],
                ['label' => $label, 'group' => $group, 'created_at' => $now, 'updated_at' => $now]
            );
        }

        $roles = [
            'super-admin' => ['Super Admin', 'Full system access across content, users, roles, and settings.', array_column($permissions, 0)],
            'admin' => ['Admin', 'General administrative access for managing platform content and moderation.', ['manage-dashboard', 'manage-devotionals', 'manage-categories', 'manage-prayer-requests', 'manage-testimonies', 'view-engagement', 'manage-audio-devotionals', 'manage-notifications']],
            'editor' => ['Editor', 'Creates and manages devotional and audio content.', ['manage-dashboard', 'manage-devotionals', 'manage-categories', 'manage-audio-devotionals']],
            'moderator' => ['Moderator', 'Moderates public prayer requests and testimonies.', ['manage-dashboard', 'manage-prayer-requests', 'manage-testimonies']],
            'reader' => ['Reader', 'Default devotional reader role.', []],
        ];

        foreach ($roles as $name => [$label, $description, $rolePermissions]) {
            DB::table('roles')->updateOrInsert(
                ['name' => $name],
                ['label' => $label, 'description' => $description, 'is_system' => true, 'created_at' => $now, 'updated_at' => $now]
            );

            $roleId = DB::table('roles')->where('name', $name)->value('id');
            $permissionIds = DB::table('permissions')->whereIn('name', $rolePermissions)->pluck('id');

            foreach ($permissionIds as $permissionId) {
                DB::table('permission_role')->updateOrInsert(
                    ['role_id' => $roleId, 'permission_id' => $permissionId],
                    ['created_at' => $now, 'updated_at' => $now]
                );
            }
        }

        $superAdminRoleId = DB::table('roles')->where('name', 'super-admin')->value('id');
        $adminRoleId = DB::table('roles')->where('name', 'admin')->value('id');
        $readerRoleId = DB::table('roles')->where('name', 'reader')->value('id');

        DB::table('users')->where('is_super_admin', true)->orderBy('id')->select('id')->chunkById(100, function ($users) use ($superAdminRoleId, $now) {
            foreach ($users as $user) {
                DB::table('role_user')->updateOrInsert(['user_id' => $user->id, 'role_id' => $superAdminRoleId], ['created_at' => $now, 'updated_at' => $now]);
            }
        });

        DB::table('users')->where('is_admin', true)->where('is_super_admin', false)->orderBy('id')->select('id')->chunkById(100, function ($users) use ($adminRoleId, $now) {
            foreach ($users as $user) {
                DB::table('role_user')->updateOrInsert(['user_id' => $user->id, 'role_id' => $adminRoleId], ['created_at' => $now, 'updated_at' => $now]);
            }
        });

        DB::table('users')->where('is_admin', false)->where('is_super_admin', false)->orderBy('id')->select('id')->chunkById(100, function ($users) use ($readerRoleId, $now) {
            foreach ($users as $user) {
                DB::table('role_user')->updateOrInsert(['user_id' => $user->id, 'role_id' => $readerRoleId], ['created_at' => $now, 'updated_at' => $now]);
            }
        });
    }

    public function down(): void
    {
        DB::table('role_user')->truncate();
        DB::table('permission_role')->truncate();
        DB::table('permissions')->truncate();
        DB::table('roles')->truncate();
    }
};
