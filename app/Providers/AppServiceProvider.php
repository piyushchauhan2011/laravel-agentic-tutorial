<?php

namespace App\Providers;

use App\Models\Application;
use App\Models\Candidate;
use App\Models\Position;
use App\Policies\ApplicationPolicy;
use App\Policies\CandidatePolicy;
use App\Policies\PositionPolicy;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Gate::policy(Application::class, ApplicationPolicy::class);
        Gate::policy(Candidate::class, CandidatePolicy::class);
        Gate::policy(Position::class, PositionPolicy::class);

        Relation::morphMap([
            'application' => Application::class,
            'position' => Position::class,
        ]);
    }
}
