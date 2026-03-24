<?php

namespace App\Providers;

// use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        //
    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
        \Illuminate\Support\Facades\Gate::before(function ($user, $ability) {
            if ($user->hasRole('Super Admin')) {
                return true;
            }
        });

        // Register custom permission check
        \Illuminate\Support\Facades\Gate::define('permission', function ($user, $slug) {
            return $user->hasPermission($slug);
        });
    }
}
