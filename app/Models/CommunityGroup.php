<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class CommunityGroup extends Model
{
    protected $fillable = [
        'owner_id',
        'name',
        'slug',
        'ministry_type',
        'description',
        'visibility',
        'invite_enabled',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'invite_enabled' => 'boolean',
            'is_active' => 'boolean',
        ];
    }

    public function owner(): BelongsTo
    {
        return $this->belongsTo(User::class, 'owner_id');
    }

    public function memberships(): HasMany
    {
        return $this->hasMany(CommunityGroupMembership::class);
    }

    public function members(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'community_group_memberships')
            ->withPivot([
                'role',
                'joined_at',
                'last_read_on',
                'current_reading_streak',
                'longest_reading_streak',
                'completed_chapters_count',
            ])
            ->withTimestamps();
    }

    public function invites(): HasMany
    {
        return $this->hasMany(CommunityGroupInvite::class);
    }

    public function readingChallenges(): HasMany
    {
        return $this->hasMany(CommunityGroupReadingChallenge::class);
    }

    public function activeChallenges(): HasMany
    {
        return $this->readingChallenges()->open();
    }

    public function readingLogs(): HasMany
    {
        return $this->hasMany(CommunityGroupReadingLog::class);
    }

    public function prayers(): HasMany
    {
        return $this->hasMany(CommunityGroupPrayer::class);
    }

    public function scopeActive(Builder $query): Builder
    {
        return $query->where('is_active', true);
    }

    public function scopeDiscoverable(Builder $query): Builder
    {
        return $query->active()->where('visibility', 'public');
    }

    public function membershipFor(?User $user): ?CommunityGroupMembership
    {
        if (! $user) {
            return null;
        }

        return $this->memberships->firstWhere('user_id', $user->id)
            ?: $this->memberships()->where('user_id', $user->id)->first();
    }

    public function isMember(?User $user): bool
    {
        return (bool) $this->membershipFor($user);
    }

    public function canManage(?User $user): bool
    {
        $membership = $this->membershipFor($user);

        return $user?->hasAdminAccess()
            || $this->owner_id === $user?->id
            || in_array($membership?->role, ['owner', 'leader'], true);
    }

    public function typeLabel(): string
    {
        return match ($this->ministry_type) {
            'church' => 'Church',
            'ministry' => 'Ministry',
            'youth' => 'Youth group',
            default => 'Small group',
        };
    }
}
