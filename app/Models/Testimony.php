<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Testimony extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'name',
        'title',
        'body',
        'category',
        'before_body',
        'after_body',
        'answered_on',
        'is_anonymous',
        'is_approved',
        'moderation_status',
        'moderation_notes',
        'moderated_by',
        'moderated_at',
    ];

    protected function casts(): array
    {
        return [
            'answered_on' => 'date',
            'is_anonymous' => 'boolean',
            'is_approved' => 'boolean',
            'moderated_at' => 'datetime',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function moderator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'moderated_by');
    }

    public function categoryLabel(): string
    {
        return match ($this->category ?: 'breakthrough') {
            'healing' => 'Healing',
            'family' => 'Family',
            'business' => 'Business',
            'exams' => 'Exams',
            'marriage' => 'Marriage',
            'salvation' => 'Salvation',
            'provision' => 'Provision',
            'breakthrough' => 'Breakthrough',
            default => str($this->category)->headline()->toString(),
        };
    }
}
