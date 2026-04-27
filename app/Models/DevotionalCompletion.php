<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DevotionalCompletion extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'devotional_id',
        'completed_on',
    ];

    protected function casts(): array
    {
        return [
            'completed_on' => 'date',
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
}
