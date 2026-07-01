<?php

namespace Tests\Feature;

use App\Livewire\Auth\Register;
use App\Models\GrowthEvent;
use App\Models\User;
use App\Support\GrowthAnalytics;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class GrowthAnalyticsTest extends TestCase
{
    use RefreshDatabase;

    public function test_daily_shared_link_view_tracks_country_language_and_signup_attribution(): void
    {
        $this->withHeader('CF-IPCountry', 'NG')
            ->get('/fr/daily/2026-07-01?share=daily-card&sid=test-share&lang=fr&utm_medium=share&utm_campaign=daily-devotion')
            ->assertOk();

        $this->assertDatabaseHas('growth_events', [
            'event_type' => 'daily_page_view',
            'country_code' => 'NG',
            'language' => 'fr',
            'source' => 'shared_link',
            'share_id' => 'test-share',
            'daily_date' => '2026-07-01',
        ]);

        $sharedRequest = request();
        $user = User::factory()->create(['email' => 'shared-reader@example.com']);

        GrowthAnalytics::trackSignup($sharedRequest, $user);

        $this->assertDatabaseHas('growth_events', [
            'event_type' => 'signup',
            'source' => 'shared_link',
            'share_id' => 'test-share',
            'language' => 'fr',
            'daily_date' => '2026-07-01',
        ]);

        Livewire::test(Register::class)
            ->set('name', 'Shared Reader')
            ->set('email', 'direct-reader@example.com')
            ->set('password', 'Password123!')
            ->set('password_confirmation', 'Password123!')
            ->call('register')
            ->assertRedirect(route('onboarding'));

        $this->assertDatabaseHas('growth_events', [
            'event_type' => 'signup',
            'source' => 'direct',
        ]);
    }

    public function test_browser_growth_event_endpoint_tracks_card_clicks_and_installs(): void
    {
        $this->withHeader('CF-IPCountry', 'ES')
            ->postJson(route('analytics.events'), [
                'event_type' => 'shared_card_click',
                'language' => 'es',
                'daily_date' => '2026-07-01',
                'share_channel' => 'whatsapp',
                'share_id' => 'daily-share',
                'source' => 'daily_card',
                'medium' => 'share',
                'campaign' => 'daily-devotion',
                'url' => route('daily.localized.show', ['locale' => 'es', 'date' => '2026-07-01']),
                'path' => '/es/daily/2026-07-01',
            ])
            ->assertNoContent();

        $this->assertDatabaseHas('growth_events', [
            'event_type' => 'shared_card_click',
            'country_code' => 'ES',
            'language' => 'es',
            'share_channel' => 'whatsapp',
            'share_id' => 'daily-share',
        ]);

        $this->postJson(route('analytics.events'), [
            'event_type' => 'pwa_install',
            'language' => 'yo',
            'standalone' => true,
            'display_mode' => 'standalone',
        ])->assertNoContent();

        $this->assertDatabaseHas('growth_events', [
            'event_type' => 'pwa_install',
            'language' => 'yo',
        ]);
    }

    public function test_admin_engagement_page_shows_growth_tracking_summary(): void
    {
        GrowthEvent::create([
            'event_type' => 'daily_page_view',
            'event_date' => now()->toDateString(),
            'country_code' => 'NG',
            'language' => 'yo',
            'daily_date' => '2026-07-01',
        ]);

        GrowthEvent::create([
            'event_type' => 'shared_card_click',
            'event_date' => now()->toDateString(),
            'country_code' => 'NG',
            'language' => 'yo',
            'share_channel' => 'copy',
        ]);

        $admin = \App\Models\User::factory()->create(['is_admin' => true]);

        $this->actingAs($admin)
            ->get(route('admin.engagement'))
            ->assertOk()
            ->assertSee('Growth tracking')
            ->assertSee('Daily views')
            ->assertSee('Shared card clicks')
            ->assertSee('Shared signups')
            ->assertSee('NG')
            ->assertSee('YO');
    }
}
