<?php

namespace App\Livewire\DevotionalPlans;

use App\Models\DevotionalPlanCompletion;
use App\Models\PersonalizedDailyPathCheckIn;
use App\Support\DevotionalPlans;
use App\Support\PersonalizedDailyPath;
use Carbon\CarbonImmutable;
use Livewire\Component;

class Show extends Component
{
    public string $plan;

    public function mount(string $plan): void
    {
        abort_unless(DevotionalPlans::find($plan), 404);

        $this->plan = $plan;
    }

    public function completeDay(int $dayNumber, ?int $devotionalId = null)
    {
        if (! auth()->check()) {
            return redirect()->route('login');
        }

        $definition = DevotionalPlans::find($this->plan);
        abort_unless($definition && $dayNumber >= 1 && $dayNumber <= (int) $definition['duration'], 404);

        DevotionalPlanCompletion::updateOrCreate(
            [
                'user_id' => auth()->id(),
                'plan_slug' => $this->plan,
                'day_number' => $dayNumber,
            ],
            [
                'devotional_id' => $devotionalId,
                'completed_on' => today()->toDateString(),
            ],
        );

        $this->markPathDevotionalComplete();

        session()->flash('status', 'Plan day marked complete.');

        return null;
    }

    private function markPathDevotionalComplete(): void
    {
        $profile = auth()->user()->spiritualProfile()->first();
        $path = PersonalizedDailyPath::forSeason($profile?->season);

        PersonalizedDailyPathCheckIn::updateOrCreate(
            [
                'user_id' => auth()->id(),
                'checked_on' => CarbonImmutable::today()->toDateString(),
            ],
            [
                'season_key' => $path['key'],
                'devotional_id' => $path['devotional']?->id,
                'bible_reference' => $path['definition']['reference'],
                'devotional_completed_at' => now(),
            ],
        );
    }

    public function render()
    {
        $definition = DevotionalPlans::find($this->plan);
        abort_unless($definition, 404);

        $days = DevotionalPlans::days($definition);
        $completions = auth()->check()
            ? DevotionalPlanCompletion::where('user_id', auth()->id())
                ->where('plan_slug', $this->plan)
                ->get()
                ->keyBy('day_number')
            : collect();

        return view('livewire.devotional-plans.show', [
            'planDefinition' => $definition,
            'days' => $days,
            'completions' => $completions,
            'completedCount' => $completions->count(),
            'progressPercent' => round(($completions->count() / (int) $definition['duration']) * 100),
        ]);
    }
}
