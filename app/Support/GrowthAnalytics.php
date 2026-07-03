<?php

namespace App\Support;

use App\Models\GrowthEvent;
use App\Models\User;
use Carbon\CarbonImmutable;
use Carbon\CarbonInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;

class GrowthAnalytics
{
    public const SESSION_ATTRIBUTION_KEY = 'mannarise_shared_attribution';

    private const ATTRIBUTION_DAYS = 30;

    /**
     * @param  array<string, mixed>  $attributes
     */
    public static function track(string $eventType, Request $request, array $attributes = []): GrowthEvent
    {
        self::rememberSharedAttribution($request, $attributes);

        $attribution = self::sharedAttribution($request);
        $eventDate = now()->toDateString();
        $refCode = self::refCode($request, $attributes) ?? ($attribution['ref_code'] ?? null);
        $shareChannel = $attributes['share_channel'] ?? self::requestShareChannel($request, $refCode) ?? ($attribution['share_channel'] ?? null);
        $source = $attributes['source'] ?? self::source($request);

        if ($source === 'direct' && $attribution && self::inheritsSharedAttribution($eventType)) {
            $source = 'shared_link';
        }

        $metadata = Arr::only($attributes, ['install_outcome', 'standalone', 'display_mode', 'screen', 'timezone']);
        $metadata['ref_code'] = $refCode;

        if ($source === 'shared_link' && $attribution) {
            $metadata['attribution'] = Arr::only($attribution, ['ref_code', 'share_channel', 'share_id', 'path', 'url', 'captured_at']);
        }

        return GrowthEvent::create([
            'user_id' => $attributes['user_id'] ?? $request->user()?->id,
            'event_type' => Str::limit($eventType, 64, ''),
            'event_date' => $attributes['event_date'] ?? $eventDate,
            'country_code' => self::countryCode($request),
            'language' => self::language($request, $attributes['language'] ?? ($attribution['language'] ?? null)),
            'daily_date' => $attributes['daily_date'] ?? $request->query('daily_date') ?? ($attribution['daily_date'] ?? null),
            'source' => $source,
            'medium' => $attributes['medium'] ?? $request->query('utm_medium') ?? ($attribution['medium'] ?? null),
            'campaign' => $attributes['campaign'] ?? $request->query('utm_campaign') ?? ($attribution['campaign'] ?? null),
            'share_channel' => $shareChannel,
            'share_id' => $attributes['share_id'] ?? self::requestShareId($request) ?? ($attribution['share_id'] ?? null),
            'path' => Str::limit($attributes['path'] ?? $request->path(), 255, ''),
            'url' => $attributes['url'] ?? $request->fullUrl(),
            'referrer' => $attributes['referrer'] ?? $request->headers->get('referer'),
            'ip_hash' => self::ipHash($request),
            'user_agent' => Str::limit((string) $request->userAgent(), 255, ''),
            'metadata' => array_filter($metadata, fn ($value): bool => $value !== null && $value !== ''),
        ]);
    }

    public static function trackDailyPageView(Request $request, CarbonInterface $date, string $language, string $shareId): GrowthEvent
    {
        return self::track('daily_page_view', $request, [
            'daily_date' => $date->toDateString(),
            'language' => $language,
            'share_id' => self::requestShareId($request) ?: $shareId,
            'source' => self::source($request),
        ]);
    }

    public static function trackSignup(Request $request, User $user): GrowthEvent
    {
        $attribution = self::sharedAttribution($request);

        $event = self::track('signup', $request, [
            'user_id' => $user->id,
            'daily_date' => $attribution['daily_date'] ?? null,
            'language' => $attribution['language'] ?? null,
            'source' => $attribution ? 'shared_link' : 'direct',
            'medium' => $attribution['medium'] ?? null,
            'campaign' => $attribution['campaign'] ?? null,
            'share_id' => $attribution['share_id'] ?? null,
            'share_channel' => $attribution['share_channel'] ?? null,
            'ref' => $attribution['ref_code'] ?? null,
            'url' => $attribution['url'] ?? $request->fullUrl(),
            'path' => $attribution['path'] ?? $request->path(),
        ]);

        return $event;
    }

    /**
     * @param  array<string, mixed>  $attributes
     */
    private static function rememberSharedAttribution(Request $request, array $attributes = []): void
    {
        if (! $request->hasSession() || ! self::hasReferralQuery($request)) {
            return;
        }

        $refCode = self::refCode($request, $attributes);

        $request->session()->put(self::SESSION_ATTRIBUTION_KEY, [
            'share_id' => $attributes['share_id'] ?? self::requestShareId($request),
            'language' => $attributes['language'] ?? self::language($request),
            'daily_date' => $attributes['daily_date'] ?? $request->query('daily_date'),
            'source' => 'shared_link',
            'medium' => $attributes['medium'] ?? $request->query('utm_medium'),
            'campaign' => $attributes['campaign'] ?? $request->query('utm_campaign'),
            'share_channel' => $attributes['share_channel'] ?? self::requestShareChannel($request, $refCode),
            'ref_code' => $refCode,
            'path' => $request->path(),
            'url' => $request->fullUrl(),
            'captured_at' => now()->toIso8601String(),
        ]);
    }

    /**
     * @return array<string, mixed>|null
     */
    private static function sharedAttribution(Request $request): ?array
    {
        if (! $request->hasSession()) {
            return null;
        }

        $attribution = $request->session()->get(self::SESSION_ATTRIBUTION_KEY);

        if (! is_array($attribution)) {
            return null;
        }

        $capturedAt = isset($attribution['captured_at']) ? CarbonImmutable::parse((string) $attribution['captured_at']) : null;

        if ($capturedAt && $capturedAt->lt(now()->subDays(self::ATTRIBUTION_DAYS))) {
            $request->session()->forget(self::SESSION_ATTRIBUTION_KEY);

            return null;
        }

        return $attribution;
    }

    public static function trackedShareUrl(string $url, string $language, CarbonInterface $date, string $shareId): string
    {
        return self::trackedReferralUrl($url, 'share_link', $language, $date, $shareId, [
            'share' => 'daily-card',
            'utm_campaign' => 'daily-devotion',
        ]);
    }

    /**
     * @param  array<string, string|null>  $parameters
     */
    public static function trackedReferralUrl(string $url, string $refCode, ?string $language = null, ?CarbonInterface $date = null, ?string $shareId = null, array $parameters = []): string
    {
        $separator = str_contains($url, '?') ? '&' : '?';
        $query = array_merge([
            'ref' => self::sanitizeRefCode($refCode) ?: 'share_link',
            'sid' => $shareId,
            'lang' => $language,
            'daily_date' => $date?->toDateString(),
            'utm_source' => 'mannarise',
            'utm_medium' => 'share',
            'utm_campaign' => 'referral',
            'utm_content' => $date?->toDateString(),
        ], $parameters);

        return $url.$separator.http_build_query(array_filter($query, fn ($value): bool => $value !== null && $value !== ''));
    }

    public static function makeShareId(string $seed): string
    {
        return substr(hash('sha256', $seed), 0, 20);
    }

    public static function isReferralRequest(Request $request): bool
    {
        return self::hasReferralQuery($request);
    }

    private static function countryCode(Request $request): ?string
    {
        foreach (['CF-IPCountry', 'X-AppEngine-Country', 'X-Country-Code', 'X-Forwarded-Country'] as $header) {
            $country = strtoupper(trim((string) $request->headers->get($header)));

            if (preg_match('/^[A-Z]{2}$/', $country) && $country !== 'XX') {
                return $country;
            }
        }

        return null;
    }

    private static function language(Request $request, ?string $language = null): ?string
    {
        $language = $language ?: $request->query('lang') ?: $request->route('locale');

        if (! $language) {
            $language = Str::before((string) $request->getPreferredLanguage(LanguagePages::codes()), '-');
        }

        $language = strtolower(Str::limit((string) $language, 12, ''));

        return $language !== '' ? $language : null;
    }

    private static function source(Request $request): string
    {
        if (self::hasReferralQuery($request)) {
            return 'shared_link';
        }

        return $request->query('utm_source') ?: 'direct';
    }

    private static function hasReferralQuery(Request $request): bool
    {
        return $request->query('ref')
            || $request->query('share')
            || $request->query('sid')
            || $request->query('share_id')
            || $request->query('utm_medium') === 'share';
    }

    private static function requestShareId(Request $request): ?string
    {
        $shareId = $request->query('sid') ?: $request->query('share_id');
        $shareId = is_string($shareId) ? Str::limit($shareId, 80, '') : null;

        return $shareId !== '' ? $shareId : null;
    }

    /**
     * @param  array<string, mixed>  $attributes
     */
    private static function refCode(Request $request, array $attributes = []): ?string
    {
        return self::sanitizeRefCode($attributes['ref'] ?? $attributes['ref_code'] ?? $request->query('ref'));
    }

    private static function sanitizeRefCode(mixed $value): ?string
    {
        if (! is_string($value)) {
            return null;
        }

        $value = strtolower(Str::limit(trim($value), 80, ''));
        $value = (string) preg_replace('/[^a-z0-9_-]+/', '_', $value);
        $value = trim($value, '_-');

        return $value !== '' ? $value : null;
    }

    private static function requestShareChannel(Request $request, ?string $refCode = null): ?string
    {
        $channel = $request->query('share_channel');

        if (! is_string($channel) && $refCode && str_starts_with($refCode, 'share_')) {
            $channel = Str::after($refCode, 'share_');
        }

        if (! is_string($channel)) {
            return null;
        }

        $channel = self::sanitizeRefCode($channel);

        return $channel !== '' ? $channel : null;
    }

    private static function inheritsSharedAttribution(string $eventType): bool
    {
        return in_array($eventType, ['install_prompt_click', 'pray_with_me_click', 'prayer_partner_room_view', 'prayer_partner_prayed', 'pwa_install', 'signup'], true);
    }

    private static function ipHash(Request $request): ?string
    {
        $ip = $request->ip();

        return $ip ? hash('sha256', $ip.'|'.(string) config('app.key')) : null;
    }
}
