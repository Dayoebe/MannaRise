<?php

namespace App\Support;

use App\Models\GrowthEvent;
use App\Models\User;
use Carbon\CarbonInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;

class GrowthAnalytics
{
    public const SESSION_ATTRIBUTION_KEY = 'mannarise_shared_attribution';

    /**
     * @param  array<string, mixed>  $attributes
     */
    public static function track(string $eventType, Request $request, array $attributes = []): GrowthEvent
    {
        self::rememberSharedAttribution($request, $attributes);

        $eventDate = now()->toDateString();
        $metadata = Arr::only($attributes, ['install_outcome', 'standalone', 'display_mode', 'screen', 'timezone']);

        return GrowthEvent::create([
            'user_id' => $attributes['user_id'] ?? $request->user()?->id,
            'event_type' => Str::limit($eventType, 64, ''),
            'event_date' => $attributes['event_date'] ?? $eventDate,
            'country_code' => self::countryCode($request),
            'language' => self::language($request, $attributes['language'] ?? null),
            'daily_date' => $attributes['daily_date'] ?? null,
            'source' => $attributes['source'] ?? self::source($request),
            'medium' => $attributes['medium'] ?? $request->query('utm_medium'),
            'campaign' => $attributes['campaign'] ?? $request->query('utm_campaign'),
            'share_channel' => $attributes['share_channel'] ?? null,
            'share_id' => $attributes['share_id'] ?? self::requestShareId($request),
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
            'url' => $attribution['url'] ?? $request->fullUrl(),
            'path' => $attribution['path'] ?? $request->path(),
        ]);

        if ($attribution) {
            $request->session()->forget(self::SESSION_ATTRIBUTION_KEY);
        }

        return $event;
    }

    /**
     * @param  array<string, mixed>  $attributes
     */
    private static function rememberSharedAttribution(Request $request, array $attributes = []): void
    {
        if (! $request->hasSession()) {
            return;
        }

        $source = $attributes['source'] ?? self::source($request);

        if ($source !== 'shared_link' && ! self::requestShareId($request)) {
            return;
        }

        $request->session()->put(self::SESSION_ATTRIBUTION_KEY, [
            'share_id' => $attributes['share_id'] ?? self::requestShareId($request),
            'language' => $attributes['language'] ?? self::language($request),
            'daily_date' => $attributes['daily_date'] ?? null,
            'source' => $source,
            'medium' => $attributes['medium'] ?? $request->query('utm_medium'),
            'campaign' => $attributes['campaign'] ?? $request->query('utm_campaign'),
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

        return is_array($attribution) ? $attribution : null;
    }

    public static function trackedShareUrl(string $url, string $language, CarbonInterface $date, string $shareId): string
    {
        $separator = str_contains($url, '?') ? '&' : '?';

        return $url.$separator.http_build_query([
            'share' => 'daily-card',
            'sid' => $shareId,
            'lang' => $language,
            'utm_source' => 'mannarise',
            'utm_medium' => 'share',
            'utm_campaign' => 'daily-devotion',
            'utm_content' => $date->toDateString(),
        ]);
    }

    public static function makeShareId(string $seed): string
    {
        return substr(hash('sha256', $seed), 0, 20);
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
        if ($request->query('share') || $request->query('sid') || $request->query('utm_medium') === 'share') {
            return 'shared_link';
        }

        return $request->query('utm_source') ?: 'direct';
    }

    private static function requestShareId(Request $request): ?string
    {
        $shareId = $request->query('sid') ?: $request->query('share_id');
        $shareId = is_string($shareId) ? Str::limit($shareId, 80, '') : null;

        return $shareId !== '' ? $shareId : null;
    }

    private static function ipHash(Request $request): ?string
    {
        $ip = $request->ip();

        return $ip ? hash('sha256', $ip.'|'.(string) config('app.key')) : null;
    }
}
