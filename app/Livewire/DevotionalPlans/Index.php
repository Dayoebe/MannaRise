<?php

namespace App\Livewire\DevotionalPlans;

use App\Models\DevotionalPlanCompletion;
use App\Support\DevotionalPlans;
use Livewire\Component;

class Index extends Component
{
    public function render()
    {
        return view('livewire.devotional-plans.index', [
            'plans' => collect(DevotionalPlans::all())
                ->map(function (array $plan): array {
                    $completed = auth()->check()
                        ? DevotionalPlanCompletion::where('user_id', auth()->id())
                            ->where('plan_slug', $plan['slug'])
                            ->count()
                        : 0;

                    return [
                        ...$plan,
                        'completed_days' => $completed,
                        'progress_percent' => round(($completed / (int) $plan['duration']) * 100),
                    ];
                })
                ->values(),
        ]);
    }
}
