<?php

namespace Tests\Feature;

use App\Models\Devotional;
use App\Models\DevotionalCategory;
use App\Models\Testimony;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class MannaRisePagesTest extends TestCase
{
    use RefreshDatabase;

    public function test_public_pages_render(): void
    {
        $category = DevotionalCategory::create([
            'name' => 'Faith',
            'slug' => 'faith',
            'is_active' => true,
        ]);

        Devotional::create([
            'devotional_category_id' => $category->id,
            'title' => 'Faith for Today',
            'slug' => 'faith-for-today',
            'content' => 'A devotional body long enough for validation and display.',
            'published_at' => now(),
            'is_published' => true,
            'is_featured' => true,
            'reading_time' => 4,
        ]);

        Testimony::create([
            'title' => 'A good report',
            'body' => 'This is an approved testimony for public display.',
            'is_approved' => true,
        ]);

        foreach (['/', '/daily', '/bible', '/library', '/devotionals', '/devotionals/faith-for-today', '/prayer-wall', '/prayer-request', '/testimonies', '/testimony', '/login', '/register'] as $path) {
            $this->get($path)->assertOk();
        }
    }

    public function test_authenticated_pages_render(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user);

        foreach (['/dashboard', '/growth-path', '/journal', '/favorites'] as $path) {
            $this->get($path)->assertOk();
        }
    }

    public function test_admin_pages_render(): void
    {
        $admin = User::factory()->create(['is_admin' => true]);

        $this->actingAs($admin);

        foreach (['/admin', '/admin/categories', '/admin/devotionals', '/admin/prayer-requests', '/admin/testimonies', '/admin/engagement', '/admin/settings'] as $path) {
            $this->get($path)->assertOk();
        }
    }

    public function test_super_admin_has_admin_access(): void
    {
        $superAdmin = User::factory()->create([
            'is_admin' => false,
            'is_super_admin' => true,
        ]);

        $this->actingAs($superAdmin);

        foreach (['/admin', '/admin/categories', '/admin/devotionals', '/admin/prayer-requests', '/admin/testimonies', '/admin/engagement', '/admin/settings'] as $path) {
            $this->get($path)->assertOk();
        }
    }
}
