<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DailyRhythmCheckIn extends Model
{
    protected $fillable = [
        'user_id',
        'checked_on',
        'verse_reference',
        'affirmation_reference',
        'bible_reading_label',
        'verse_completed_at',
        'affirmation_completed_at',
        'challenge_completed_at',
    ];

    protected function casts(): array
    {
        return [
            'checked_on' => 'date',
            'verse_completed_at' => 'datetime',
            'affirmation_completed_at' => 'datetime',
            'challenge_completed_at' => 'datetime',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
