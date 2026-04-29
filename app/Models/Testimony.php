<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Testimony extends Model
{
    use HasFactory;

    public const STATUS_PENDING = 'pending';
    public const STATUS_APPROVED = 'approved';
    public const STATUS_REJECTED = 'rejected';

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

    public function scopeApproved(Builder $query): Builder
    {
        return $query->where('moderation_status', self::STATUS_APPROVED);
    }

    public function scopePending(Builder $query): Builder
    {
        return $query->where('moderation_status', self::STATUS_PENDING);
    }

    public function scopeRejected(Builder $query): Builder
    {
        return $query->where('moderation_status', self::STATUS_REJECTED);
    }

    public function approve(?int $moderatorId = null): bool
    {
        return $this->update([
            'is_approved' => true,
            'moderation_status' => self::STATUS_APPROVED,
            'moderated_by' => $moderatorId,
            'moderated_at' => now(),
        ]);
    }

    public function reject(?int $moderatorId = null, ?string $notes = null): bool
    {
        return $this->update([
            'is_approved' => false,
            'moderation_status' => self::STATUS_REJECTED,
            'moderation_notes' => $notes,
            'moderated_by' => $moderatorId,
            'moderated_at' => now(),
        ]);
    }

    public function markPending(?int $moderatorId = null): bool
    {
        return $this->update([
            'is_approved' => false,
            'moderation_status' => self::STATUS_PENDING,
            'moderated_by' => $moderatorId,
            'moderated_at' => $moderatorId ? now() : null,
        ]);
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
