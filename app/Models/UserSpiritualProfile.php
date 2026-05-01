<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UserSpiritualProfile extends Model
{
    protected $fillable = [
        'user_id',
        'season',
        'seasons',
        'path_goal',
        'support_note',
        'preferred_time',
    ];

    protected function casts(): array
    {
        return [
            'seasons' => 'array',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
