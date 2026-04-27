<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PrayerRequest extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'name',
        'email',
        'title',
        'body',
        'is_public',
        'is_answered',
        'prayed_count',
    ];

    protected function casts(): array
    {
        return [
            'is_public' => 'boolean',
            'is_answered' => 'boolean',
            'prayed_count' => 'integer',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
