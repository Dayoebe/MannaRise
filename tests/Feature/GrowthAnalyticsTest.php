<?php

namespace Tests\Feature;

use App\Livewire\Auth\Register;
use App\Models\GrowthEvent;
use App\Models\PrayerPartnerRoom;
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
            ->get('/fr/daily/2026-07-01?ref=share_whatsapp&share=daily-card&sid=test-share&lang=fr&utm_medium=share&utm_campaign=daily-devotion')
            ->assertOk();

        $this->assertDatabaseHas('growth_events', [
            'event_type' => 'daily_page_view',
            'country_code' => 'NG',
            'language' => 'fr',
            'source' => 'shared_link',
            'share_channel' => 'whatsapp',
            'share_id' => 'test-share',
            'daily_date' => '2026-07-01',
        ]);

        $view = GrowthEvent::query()->where('event_type', 'daily_page_view')->firstOrFail();
        $this->assertSame('share_whatsapp', $view->metadata['ref_code'] ?? null);

        $this->postJson(route('analytics.events'), [
            'event_type' => 'pray_with_me_click',
            'ref' => 'pray_with_me',
            'share_channel' => 'pray',
            'url' => route('prayer-invites.show'),
            'path' => '/fr/daily/2026-07-01',
        ])->assertNoContent();

        $this->assertDatabaseHas('growth_events', [
            'event_type' => 'pray_with_me_click',
            'source' => 'shared_link',
            'share_channel' => 'pray',
            'share_id' => 'test-share',
            'language' => 'fr',
            'daily_date' => '2026-07-01',
        ]);

        $this->postJson(route('analytics.events'), [
            'event_type' => 'pwa_install',
            'standalone' => true,
            'display_mode' => 'standalone',
        ])->assertNoContent();

        $this->assertDatabaseHas('growth_events', [
            'event_type' => 'pwa_install',
            'source' => 'shared_link',
            'share_channel' => 'whatsapp',
            'share_id' => 'test-share',
            'language' => 'fr',
            'daily_date' => '2026-07-01',
        ]);

        $sharedRequest = request();
        $user = User::factory()->create(['email' => 'shared-reader@example.com']);

        GrowthAnalytics::trackSignup($sharedRequest, $user);

        $this->assertDatabaseHas('growth_events', [
            'event_type' => 'signup',
            'source' => 'shared_link',
            'share_id' => 'test-share',
            'share_channel' => 'whatsapp',
            'language' => 'fr',
            'daily_date' => '2026-07-01',
        ]);

        session()->forget(GrowthAnalytics::SESSION_ATTRIBUTION_KEY);

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

    public function test_daily_share_links_and_prayer_invite_carry_referral_codes(): void
    {
        $this->get('/es/daily/2026-07-01')
            ->assertOk()
            ->assertSee('ref=share_link', false)
            ->assertSee('ref=pray_with_me', false)
            ->assertSee('Invitar a alguien a orar contigo');

        $this->get('/pray-with-me?ref=pray_with_me&sid=chain-share&lang=es&daily_date=2026-07-01&utm_medium=share')
            ->assertOk()
            ->assertSee('ref=share_link', false)
            ->assertSee('sid=chain-share', false)
            ->assertSee('/pray-together/', false);

        $this->assertDatabaseHas('growth_events', [
            'event_type' => 'prayer_invite_view',
            'source' => 'shared_link',
            'share_id' => 'chain-share',
            'language' => 'es',
            'daily_date' => '2026-07-01',
        ]);

        $partnerRoom = PrayerPartnerRoom::query()
            ->where('source_type', 'daily')
            ->where('source_key', '2026-07-01')
            ->where('share_id', 'chain-share')
            ->firstOrFail();

        $this->get(route('prayer-partners.show', ['token' => $partnerRoom->token]).'?ref=share_whatsapp&sid=chain-share&lang=es&daily_date=2026-07-01&utm_medium=share')
            ->assertOk()
            ->assertSee('Prayer partner room')
            ->assertSee('data-prayer-partner-share', false);

        $this->assertDatabaseHas('growth_events', [
            'event_type' => 'prayer_partner_room_view',
            'source' => 'shared_link',
            'share_id' => 'chain-share',
            'language' => 'es',
            'daily_date' => '2026-07-01',
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

        GrowthEvent::create([
            'event_type' => 'pray_with_me_click',
            'event_date' => now()->toDateString(),
            'source' => 'shared_link',
            'share_channel' => 'pray',
        ]);

        GrowthEvent::create([
            'event_type' => 'pwa_install',
            'event_date' => now()->toDateString(),
            'source' => 'shared_link',
            'share_channel' => 'whatsapp',
        ]);

        $admin = User::factory()->create(['is_admin' => true]);

        $this->actingAs($admin)
            ->get(route('admin.engagement'))
            ->assertOk()
            ->assertSee('Growth tracking')
            ->assertSee('Daily views')
            ->assertSee('Shared card clicks')
            ->assertSee('Pray clicks')
            ->assertSee('Shared installs')
            ->assertSee('Shared signups')
            ->assertSee('NG')
            ->assertSee('YO');
    }
}
