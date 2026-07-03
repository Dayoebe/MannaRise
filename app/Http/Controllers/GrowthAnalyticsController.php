<?php

namespace App\Http\Controllers;

use App\Support\GrowthAnalytics;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Validation\Rule;

class GrowthAnalyticsController extends Controller
{
    public function store(Request $request): Response
    {
        $validated = $request->validate([
            'event_type' => ['required', 'string', Rule::in([
                'shared_card_click',
                'pray_with_me_click',
                'prayer_partner_prayed',
                'install_prompt_click',
                'pwa_install',
            ])],
            'language' => ['nullable', 'string', 'max:12'],
            'daily_date' => ['nullable', 'date_format:Y-m-d'],
            'share_channel' => ['nullable', 'string', 'max:64'],
            'share_id' => ['nullable', 'string', 'max:80'],
            'ref' => ['nullable', 'string', 'max:80'],
            'url' => ['nullable', 'url', 'max:2048'],
            'path' => ['nullable', 'string', 'max:255'],
            'source' => ['nullable', 'string', 'max:64'],
            'medium' => ['nullable', 'string', 'max:64'],
            'campaign' => ['nullable', 'string', 'max:128'],
            'install_outcome' => ['nullable', 'string', 'max:32'],
            'standalone' => ['nullable', 'boolean'],
            'display_mode' => ['nullable', 'string', 'max:32'],
            'screen' => ['nullable', 'array'],
            'timezone' => ['nullable', 'string', 'max:80'],
        ]);

        GrowthAnalytics::track($validated['event_type'], $request, $validated);

        return response('', 204);
    }
}
