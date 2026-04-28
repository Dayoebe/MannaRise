<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PrayerRequestUpdate extends Model
{
    protected $fillable = [
        'prayer_request_id',
        'user_id',
        'body',
        'is_answered_update',
    ];

    protected function casts(): array
    {
        return [
            'is_answered_update' => 'boolean',
        ];
    }

    public function request(): BelongsTo
    {
        return $this->belongsTo(PrayerRequest::class, 'prayer_request_id');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
