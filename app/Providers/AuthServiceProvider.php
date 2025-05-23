<?php

namespace App\Providers;

use App\Models\Dimensionnement;
use App\Models\Order;
use App\Policies\DimensionnementPolicy;
use App\Policies\OrderPolicy;
use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        Dimensionnement::class => DimensionnementPolicy::class,
        Order::class => OrderPolicy::class,
    ];

    /**
     * Register any authentication / authorization services.
     */    public function boot(): void
    {
        $this->registerPolicies();

        // Définir la Gate pour la gestion de la galerie
        Gate::define('manage-gallery', function ($user) {
            return $user->is_admin;
        });
    }
}
