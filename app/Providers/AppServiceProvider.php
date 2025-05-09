<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Blade;
use App\View\Components\Admin\StatsCard;
use App\View\Components\Admin\ActionCard;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */    public function boot()
    {
        \Illuminate\Support\Facades\Blade::component('admin-layout', \App\View\Components\AdminLayout::class);
        Blade::component('admin.stats-card', StatsCard::class);
        Blade::component('admin.action-card', ActionCard::class);
    }
}
