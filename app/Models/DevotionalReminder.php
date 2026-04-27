<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DevotionalReminder extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'title',
        'remind_at',
        'timezone',
        'days',
        'email_enabled',
        'push_enabled',
        'is_active',
        'last_sent_at',
    ];

    protected function casts(): array
    {
        return [
            'days' => 'array',
            'email_enabled' => 'boolean',
            'push_enabled' => 'boolean',
            'is_active' => 'boolean',
            'last_sent_at' => 'datetime',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
