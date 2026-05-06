<?php

namespace App\Livewire\CommunityGroups;

use App\Models\CommunityGroup;
use App\Models\CommunityGroupMembership;
use App\Models\CommunityGroupReadingChallenge;
use App\Support\Toast;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Livewire\Component;

class Index extends Component
{
    public string $name = '';

    public string $ministry_type = 'small_group';

    public string $description = '';

    public string $visibility = 'private';

    public function createGroup()
    {
        abort_unless(auth()->check(), 403);

        $validated = $this->validate([
            'name' => ['required', 'string', 'max:120'],
            'ministry_type' => ['required', Rule::in(['church', 'ministry', 'small_group', 'youth'])],
            'description' => ['nullable', 'string', 'max:1200'],
            'visibility' => ['required', Rule::in(['private', 'public'])],
        ]);

        $group = CommunityGroup::create([
            'owner_id' => auth()->id(),
            'name' => $validated['name'],
            'slug' => $this->uniqueSlug($validated['name']),
            'ministry_type' => $validated['ministry_type'],
            'description' => $validated['description'] ?: null,
            'visibility' => $validated['visibility'],
            'invite_enabled' => true,
            'is_active' => true,
        ]);

        CommunityGroupMembership::create([
            'community_group_id' => $group->id,
            'user_id' => auth()->id(),
            'role' => 'owner',
            'joined_at' => now(),
        ]);

        CommunityGroupReadingChallenge::create([
            'community_group_id' => $group->id,
            'title' => '30-day reading consistency',
            'description' => 'Log at least one Bible chapter each day and build a group rhythm of consistency.',
            'starts_on' => today(),
            'ends_on' => today()->addDays(29),
            'daily_chapter_goal' => 1,
            'is_active' => true,
        ]);

        $this->reset('name', 'description');
        $this->ministry_type = 'small_group';
        $this->visibility = 'private';
        $this->resetErrorBag();

        Toast::status($this, 'Community group created.');

        return redirect()->route('community-groups.show', $group->slug);
    }

    public function joinPublicGroup(int $id)
    {
        abort_unless(auth()->check(), 403);

        $group = CommunityGroup::discoverable()->findOrFail($id);

        CommunityGroupMembership::firstOrCreate(
            [
                'community_group_id' => $group->id,
                'user_id' => auth()->id(),
            ],
            [
                'role' => 'member',
                'joined_at' => now(),
            ],
        );

        Toast::status($this, "You joined {$group->name}.");

        return redirect()->route('community-groups.show', $group->slug);
    }

    public function render()
    {
        return view('livewire.community-groups.index', [
            'groups' => CommunityGroup::query()
                ->active()
                ->whereHas('memberships', fn (Builder $query) => $query->where('user_id', auth()->id()))
                ->withCount(['memberships', 'readingChallenges', 'prayers'])
                ->latest()
                ->get(),
            'discoverableGroups' => CommunityGroup::query()
                ->discoverable()
                ->whereDoesntHave('memberships', fn (Builder $query) => $query->where('user_id', auth()->id()))
                ->withCount(['memberships', 'readingChallenges'])
                ->latest()
                ->take(6)
                ->get(),
        ]);
    }

    private function uniqueSlug(string $name): string
    {
        $base = Str::slug($name) ?: 'group';
        $slug = $base;
        $suffix = 2;

        while (CommunityGroup::where('slug', $slug)->exists()) {
            $slug = "{$base}-{$suffix}";
            $suffix++;
        }

        return $slug;
    }
}
