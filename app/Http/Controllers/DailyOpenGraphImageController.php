<?php

namespace App\Http\Controllers;

use App\Support\DailyOpenGraphCard;
use App\Support\DailySpiritualRhythm;
use App\Support\LanguagePages;
use App\Support\LocalizedDailyScripture;
use Carbon\CarbonImmutable;
use Illuminate\Http\Response;
use Throwable;

class DailyOpenGraphImageController extends Controller
{
    public function __invoke(string $locale, string $date): Response
    {
        abort_unless(LanguagePages::isSupported($locale), 404);

        try {
            $parsedDate = CarbonImmutable::createFromFormat('!Y-m-d', $date);
        } catch (Throwable) {
            $parsedDate = false;
        }

        abort_unless($parsedDate && $parsedDate->format('Y-m-d') === $date, 404);

        $dailyRhythm = DailySpiritualRhythm::forDate($parsedDate);
        $scripture = LocalizedDailyScripture::forDate($dailyRhythm, $parsedDate, $locale);
        $copy = LanguagePages::dailyCopy($locale, $dailyRhythm, $parsedDate);
        $appName = (string) config('seo.site_name', 'MannaRise');
        $appHost = (string) parse_url((string) config('app.url'), PHP_URL_HOST);

        if ($appHost === '' || in_array($appHost, ['localhost', '127.0.0.1'], true)) {
            $appHost = $appName;
        }

        $image = DailyOpenGraphCard::render([
            'title' => $copy['page_title'],
            'date' => $copy['date_label'],
            'scripture_text' => $scripture['text'],
            'scripture_reference' => $scripture['reference'],
            'affirmation' => $copy['affirmation_text'],
            'theme' => $copy['theme_label'],
            'language' => $copy['language']['native_name'] ?? strtoupper($locale),
            'daily_label' => $copy['card_devotion_label'],
            'growth_label' => $copy['card_growth_label'],
            'app_name' => $appName,
            'app_host' => $appHost,
        ]);

        return response($image, 200, [
            'Content-Type' => 'image/png',
            'Cache-Control' => 'public, max-age=86400, stale-while-revalidate=604800',
            'Content-Disposition' => 'inline; filename="mannarise-daily-'.$locale.'-'.$date.'.png"',
        ]);
    }
}
