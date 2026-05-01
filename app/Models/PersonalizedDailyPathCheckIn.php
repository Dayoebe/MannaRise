<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PersonalizedDailyPathCheckIn extends Model
{
    protected $fillable = [
        'user_id',
        'checked_on',
        'season_key',
        'devotional_id',
        'bible_reference',
        'devotional_completed_at',
        'scripture_completed_at',
        'affirmation_completed_at',
        'prayer_completed_at',
        'journal_completed_at',
        'action_completed_at',
    ];

    protected function casts(): array
    {
        return [
            'checked_on' => 'date',
            'devotional_completed_at' => 'datetime',
            'scripture_completed_at' => 'datetime',
            'affirmation_completed_at' => 'datetime',
            'prayer_completed_at' => 'datetime',
            'journal_completed_at' => 'datetime',
            'action_completed_at' => 'datetime',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function devotional(): BelongsTo
    {
        return $this->belongsTo(Devotional::class);
    }

    public function completedCount(): int
    {
        return collect([
            $this->devotional_completed_at,
            $this->scripture_completed_at,
            $this->affirmation_completed_at,
            $this->prayer_completed_at,
            $this->journal_completed_at,
            $this->action_completed_at,
        ])->filter()->count();
    }

    public function progressPercent(): int
    {
        return (int) round(($this->completedCount() / 6) * 100);
    }
}
