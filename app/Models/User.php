<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'is_admin',
        'is_super_admin',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'is_admin' => 'boolean',
            'is_super_admin' => 'boolean',
        ];
    }

    public function hasAdminAccess(): bool
    {
        return $this->is_super_admin
            || $this->is_admin
            || $this->hasAnyRole(['super-admin', 'admin', 'editor', 'moderator']);
    }

    public function hasRole(string $role): bool
    {
        return $this->roles()->where('name', $role)->exists();
    }

    public function hasAnyRole(array $roles): bool
    {
        return $this->roles()->whereIn('name', $roles)->exists();
    }

    public function canDo(string $permission): bool
    {
        if ($this->is_super_admin || $this->hasRole('super-admin')) {
            return true;
        }

        return $this->roles()
            ->whereHas('permissions', fn ($query) => $query->where('name', $permission))
            ->exists();
    }

    public function assignRole(string|Role $role): void
    {
        $roleModel = is_string($role) ? Role::where('name', $role)->firstOrFail() : $role;
        $this->roles()->syncWithoutDetaching([$roleModel->id]);
    }

    public function removeRole(string|Role $role): void
    {
        $roleModel = is_string($role) ? Role::where('name', $role)->first() : $role;

        if ($roleModel) {
            $this->roles()->detach($roleModel->id);
        }
    }

    public function roles(): BelongsToMany
    {
        return $this->belongsToMany(Role::class)->withTimestamps();
    }

    public function devotionals(): HasMany
    {
        return $this->hasMany(Devotional::class);
    }

    public function journalEntries(): HasMany
    {
        return $this->hasMany(JournalEntry::class);
    }

    public function prayerRequests(): HasMany
    {
        return $this->hasMany(PrayerRequest::class);
    }

    public function testimonies(): HasMany
    {
        return $this->hasMany(Testimony::class);
    }

    public function favoriteDevotionals(): BelongsToMany
    {
        return $this->belongsToMany(Devotional::class, 'devotional_favorites')->withTimestamps();
    }

    public function devotionalCompletions(): HasMany
    {
        return $this->hasMany(DevotionalCompletion::class);
    }

    public function dailyRhythmCheckIns(): HasMany
    {
        return $this->hasMany(DailyRhythmCheckIn::class);
    }

    public function bibleChapterCompletions(): HasMany
    {
        return $this->hasMany(BibleChapterCompletion::class);
    }

    public function spiritualProfile()
    {
        return $this->hasOne(UserSpiritualProfile::class);
    }
}
