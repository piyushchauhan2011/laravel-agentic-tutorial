<?php

namespace App\Providers;

use App\Models\Application;
use App\Models\Position;
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
        Gate::policy(Position::class, PositionPolicy::class);

        Relation::morphMap([
            'application' => Application::class,
            'position' => Position::class,
        ]);
    }
}
