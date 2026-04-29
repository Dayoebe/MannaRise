<?php

namespace Tests\Feature;

use App\Models\Devotional;
use App\Models\DevotionalCategory;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ApplicationSmokeTest extends TestCase
{
    use RefreshDatabase;

    public function test_home_page_loads(): void
    {
        $this->get(route('home'))
            ->assertOk();
    }

    public function test_guest_is_redirected_from_dashboard(): void
    {
        $this->get(route('dashboard'))
            ->assertRedirect(route('login'));
    }

    public function test_authenticated_user_can_access_dashboard(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user)
            ->get(route('dashboard'))
            ->assertOk();
    }

    public function test_regular_user_cannot_access_admin_dashboard(): void
    {
        $user = User::factory()->create([
            'is_admin' => false,
            'is_super_admin' => false,
        ]);

        $this->actingAs($user)
            ->get(route('admin.dashboard'))
            ->assertForbidden();
    }

    public function test_admin_user_can_access_admin_dashboard(): void
    {
        $admin = User::factory()->create([
            'is_admin' => true,
            'is_super_admin' => false,
        ]);

        $this->actingAs($admin)
            ->get(route('admin.dashboard'))
            ->assertOk();
    }

    public function test_published_devotional_can_be_viewed_publicly(): void
    {
        $category = DevotionalCategory::create([
            'name' => 'Faith',
            'slug' => 'faith',
            'is_active' => true,
        ]);

        $devotional = Devotional::create([
            'devotional_category_id' => $category->id,
            'title' => 'Faith for Today',
            'slug' => 'faith-for-today',
            'content' => 'A short devotional reflection for today.',
            'published_at' => now(),
            'is_published' => true,
            'reading_time' => 3,
        ]);

        $this->get(route('devotionals.show', $devotional->slug))
            ->assertOk()
            ->assertSee('Faith for Today');
    }
}
