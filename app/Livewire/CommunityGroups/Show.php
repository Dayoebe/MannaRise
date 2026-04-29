<?php

namespace App\Livewire\CommunityGroups;

use App\Models\BibleBook;
use App\Models\CommunityGroup;
use App\Models\CommunityGroupInvite;
use App\Models\CommunityGroupMembership;
use App\Models\CommunityGroupPrayer;
use App\Models\CommunityGroupPrayerLog;
use App\Models\CommunityGroupReadingChallenge;
use App\Models\CommunityGroupReadingLog;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Livewire\Component;

class Show extends Component
{
    public CommunityGroup $communityGroup;

    public string $challengeTitle = '';

    public string $challengeDescription = '';

    public string $challengeStartsOn = '';

    public string $challengeEndsOn = '';

    public int $dailyChapterGoal = 1;

    public string $selectedChallengeId = '';

    public string $readingBookId = '';

    public int $readingChapter = 1;

    public string $readingNotes = '';

    public string $prayerTitle = '';

    public string $prayerBody = '';

    public string $inviteLabel = '';

    public string $inviteExpiresAt = '';

    public string $inviteMaxUses = '';

    public function mount(string $group): void
    {
        $this->communityGroup = CommunityGroup::query()
            ->with('memberships')
            ->active()
            ->where('slug', $group)
            ->firstOrFail();

        abort_unless($this->communityGroup->visibility === 'public' || $this->communityGroup->isMember(auth()->user()), 403);

        $this->challengeStartsOn = today()->toDateString();
    }

    public function join()
    {
        abort_unless(auth()->check(), 403);
        abort_unless($this->communityGroup->visibility === 'public', 403);

        $this->joinGroup();

        session()->flash('status', "You joined {$this->communityGroup->name}.");
    }

    public function createChallenge(): void
    {
        $this->authorizeLeader();

        $validated = $this->validate([
            'challengeTitle' => ['required', 'string', 'max:160'],
            'challengeDescription' => ['nullable', 'string', 'max:1200'],
            'challengeStartsOn' => ['required', 'date'],
            'challengeEndsOn' => ['nullable', 'date', 'after_or_equal:challengeStartsOn'],
            'dailyChapterGoal' => ['required', 'integer', 'min:1', 'max:12'],
        ]);

        CommunityGroupReadingChallenge::create([
            'community_group_id' => $this->communityGroup->id,
            'title' => $validated['challengeTitle'],
            'description' => $validated['challengeDescription'] ?: null,
            'starts_on' => $validated['challengeStartsOn'],
            'ends_on' => $validated['challengeEndsOn'] ?: null,
            'daily_chapter_goal' => $validated['dailyChapterGoal'],
            'is_active' => true,
        ]);

        $this->challengeTitle = '';
        $this->challengeDescription = '';
        $this->challengeStartsOn = today()->toDateString();
        $this->challengeEndsOn = '';
        $this->dailyChapterGoal = 1;

        session()->flash('status', 'Reading challenge created.');
    }

    public function logReading(): void
    {
        $membership = $this->requireMembership();
        $challenge = $this->selectedChallenge();
        abort_unless($challenge, 403);

        $validated = $this->validate([
            'selectedChallengeId' => ['nullable', 'exists:community_group_reading_challenges,id'],
            'readingBookId' => ['required', 'exists:bible_books,id'],
            'readingChapter' => ['required', 'integer', 'min:1', 'max:200'],
            'readingNotes' => ['nullable', 'string', 'max:600'],
        ]);

        $log = CommunityGroupReadingLog::firstOrCreate(
            [
                'community_group_id' => $this->communityGroup->id,
                'community_group_reading_challenge_id' => $challenge?->id,
                'user_id' => auth()->id(),
                'bible_book_id' => (int) $validated['readingBookId'],
                'chapter' => $validated['readingChapter'],
                'read_on' => today()->toDateString(),
            ],
            ['notes' => $validated['readingNotes'] ?: null],
        );

        if ($log->wasRecentlyCreated) {
            $this->updateReadingStreak($membership);
            session()->flash('status', 'Reading logged and leaderboard updated.');
        } else {
            session()->flash('status', 'That chapter is already logged for today.');
        }

        $this->readingNotes = '';
    }

    public function createPrayer(): void
    {
        $this->requireMembership();

        $validated = $this->validate([
            'prayerTitle' => ['required', 'string', 'max:160'],
            'prayerBody' => ['required', 'string', 'min:10', 'max:4000'],
        ]);

        CommunityGroupPrayer::create([
            'community_group_id' => $this->communityGroup->id,
            'user_id' => auth()->id(),
            'title' => $validated['prayerTitle'],
            'body' => $validated['prayerBody'],
        ]);

        $this->prayerTitle = '';
        $this->prayerBody = '';

        session()->flash('status', 'Prayer shared with your private circle.');
    }

    public function pray(int $id): void
    {
        $this->requireMembership();

        $prayer = CommunityGroupPrayer::where('community_group_id', $this->communityGroup->id)->findOrFail($id);

        $log = CommunityGroupPrayerLog::firstOrCreate([
            'community_group_prayer_id' => $prayer->id,
            'user_id' => auth()->id(),
            'prayed_on' => today()->toDateString(),
        ]);

        if ($log->wasRecentlyCreated) {
            $prayer->increment('prayed_count');
        }

        session()->flash('status', 'Private prayer count updated.');
    }

    public function markPrayerAnswered(int $id): void
    {
        $this->requireMembership();

        $prayer = CommunityGroupPrayer::where('community_group_id', $this->communityGroup->id)->findOrFail($id);

        abort_unless($this->communityGroup->canManage(auth()->user()) || $prayer->user_id === auth()->id(), 403);

        $prayer->update(['is_answered' => ! $prayer->is_answered]);
    }

    public function createInvite(): void
    {
        $this->authorizeLeader();

        $validated = $this->validate([
            'inviteLabel' => ['nullable', 'string', 'max:120'],
            'inviteExpiresAt' => ['nullable', 'date', 'after:now'],
            'inviteMaxUses' => ['nullable', 'integer', 'min:1', 'max:500'],
        ]);

        CommunityGroupInvite::create([
            'community_group_id' => $this->communityGroup->id,
            'created_by' => auth()->id(),
            'token' => Str::random(40),
            'label' => $validated['inviteLabel'] ?: null,
            'expires_at' => $validated['inviteExpiresAt'] ?: null,
            'max_uses' => $validated['inviteMaxUses'] === '' ? null : $validated['inviteMaxUses'],
            'is_active' => true,
        ]);

        $this->inviteLabel = '';
        $this->inviteExpiresAt = '';
        $this->inviteMaxUses = '';

        session()->flash('status', 'Invite link created.');
    }

    public function toggleInvite(int $id): void
    {
        $this->authorizeLeader();

        $invite = CommunityGroupInvite::where('community_group_id', $this->communityGroup->id)->findOrFail($id);
        $invite->update(['is_active' => ! $invite->is_active]);
    }

    public function render()
    {
        $this->communityGroup->load('memberships.user');

        $membership = $this->communityGroup->membershipFor(auth()->user());
        $canManage = $this->communityGroup->canManage(auth()->user());
        $activeChallenges = $this->communityGroup->readingChallenges()
            ->open()
            ->withCount('readingLogs')
            ->latest('starts_on')
            ->get();

        if ($this->selectedChallengeId === '' && $activeChallenges->isNotEmpty()) {
            $this->selectedChallengeId = (string) $activeChallenges->first()->id;
        }

        return view('livewire.community-groups.show', [
            'group' => $this->communityGroup,
            'membership' => $membership,
            'canManage' => $canManage,
            'activeChallenges' => $activeChallenges,
            'leaderboard' => $this->communityGroup->memberships()
                ->with('user')
                ->orderByDesc('current_reading_streak')
                ->orderByDesc('completed_chapters_count')
                ->take(12)
                ->get(),
            'recentReadingLogs' => $this->communityGroup->readingLogs()
                ->with(['user', 'book', 'challenge'])
                ->latest()
                ->take(10)
                ->get(),
            'prayers' => $this->communityGroup->prayers()
                ->with('user')
                ->latest()
                ->take(10)
                ->get(),
            'invites' => $canManage ? $this->communityGroup->invites()->latest()->take(6)->get() : collect(),
            'books' => BibleBook::orderBy('book_order')->get(),
            'todayReadingCount' => $this->communityGroup->readingLogs()->whereDate('read_on', today())->count(),
        ]);
    }

    private function selectedChallenge(): ?CommunityGroupReadingChallenge
    {
        if ($this->selectedChallengeId === '') {
            return $this->communityGroup->readingChallenges()->open()->first()
                ?: CommunityGroupReadingChallenge::create([
                    'community_group_id' => $this->communityGroup->id,
                    'title' => 'Group reading rhythm',
                    'description' => 'Shared Bible reading logs for this community.',
                    'starts_on' => today(),
                    'daily_chapter_goal' => 1,
                    'is_active' => true,
                ]);
        }

        return $this->communityGroup->readingChallenges()->whereKey((int) $this->selectedChallengeId)->first();
    }

    private function requireMembership(): CommunityGroupMembership
    {
        abort_unless(auth()->check(), 403);

        return CommunityGroupMembership::firstOrCreate(
            [
                'community_group_id' => $this->communityGroup->id,
                'user_id' => auth()->id(),
            ],
            [
                'role' => $this->communityGroup->owner_id === auth()->id() ? 'owner' : 'member',
                'joined_at' => now(),
            ],
        );
    }

    private function joinGroup(): CommunityGroupMembership
    {
        return $this->requireMembership();
    }

    private function authorizeLeader(): void
    {
        abort_unless($this->communityGroup->canManage(auth()->user()), 403);
    }

    private function updateReadingStreak(CommunityGroupMembership $membership): void
    {
        $today = today();
        $lastReadOn = $membership->last_read_on
            ? Carbon::parse($membership->last_read_on)->startOfDay()
            : null;

        if ($lastReadOn?->isSameDay($today)) {
            $currentStreak = $membership->current_reading_streak;
        } elseif ($lastReadOn?->isSameDay($today->copy()->subDay())) {
            $currentStreak = $membership->current_reading_streak + 1;
        } else {
            $currentStreak = 1;
        }

        $membership->update([
            'last_read_on' => $today,
            'current_reading_streak' => $currentStreak,
            'longest_reading_streak' => max($membership->longest_reading_streak, $currentStreak),
            'completed_chapters_count' => $membership->completed_chapters_count + 1,
        ]);
    }
}
