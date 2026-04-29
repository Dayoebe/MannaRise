<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\QueryException;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'is_admin',
        'is_super_admin',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
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
        return $this->is_super_admin || $this->is_admin || $this->canDo('manage-dashboard');
    }

    public function canDo(string $permission): bool
    {
        if ($this->is_super_admin) {
            return true;
        }

        try {
            return $this->roles()
                ->whereHas('permissions', fn ($query) => $query->where('name', $permission))
                ->exists();
        } catch (QueryException) {
            return false;
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

    public function prayerRoomMemberships(): HasMany
    {
        return $this->hasMany(PrayerRoomMembership::class);
    }

    public function prayerRooms(): BelongsToMany
    {
        return $this->belongsToMany(PrayerRoom::class, 'prayer_room_memberships')
            ->withPivot(['joined_at', 'last_prayed_on', 'current_streak', 'longest_streak', 'total_prayers'])
            ->withTimestamps();
    }

    public function prayerRoomPrayers(): HasMany
    {
        return $this->hasMany(PrayerRoomPrayer::class);
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
