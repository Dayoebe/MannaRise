<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class GrowthEvent extends Model
{
    protected $fillable = [
        'user_id',
        'event_type',
        'event_date',
        'country_code',
        'language',
        'daily_date',
        'source',
        'medium',
        'campaign',
        'share_channel',
        'share_id',
        'path',
        'url',
        'referrer',
        'ip_hash',
        'user_agent',
        'metadata',
    ];

    protected function casts(): array
    {
        return [
            'event_date' => 'date',
            'daily_date' => 'date',
            'metadata' => 'array',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
