<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PrayerRoomPrayer extends Model
{
    protected $fillable = [
        'user_id',
        'prayer_room_id',
        'prayer_request_id',
        'prayed_on',
    ];

    protected function casts(): array
    {
        return [
            'prayed_on' => 'date',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function room(): BelongsTo
    {
        return $this->belongsTo(PrayerRoom::class, 'prayer_room_id');
    }

    public function request(): BelongsTo
    {
        return $this->belongsTo(PrayerRequest::class, 'prayer_request_id');
    }
}
