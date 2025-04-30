<?php

namespace App\Providers;

use App\Services\Inverters\InverterManager;
use Illuminate\Support\ServiceProvider;

class InverterServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->singleton(InverterManager::class, function ($app) {
            return new InverterManager(config('inverters'));
        });
    }

    public function boot()
    {
        //
    }
}
