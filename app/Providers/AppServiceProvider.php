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
        // Configuration des limites PHP depuis .env
        ini_set('post_max_size', env('PHP_POST_MAX_SIZE', '128M'));
        ini_set('upload_max_filesize', env('PHP_UPLOAD_MAX_FILESIZE', '128M'));
        ini_set('memory_limit', env('PHP_MEMORY_LIMIT', '512M'));
        ini_set('max_execution_time', env('PHP_MAX_EXECUTION_TIME', '900'));
        ini_set('max_input_time', env('PHP_MAX_INPUT_TIME', '300'));

        \Illuminate\Support\Facades\Blade::component('admin-layout', \App\View\Components\AdminLayout::class);
        Blade::component('admin.stats-card', StatsCard::class);
        Blade::component('admin.action-card', ActionCard::class);
        Blade::component('logout-form', \App\View\Components\LogoutForm::class);

        $this->app->register(\App\Providers\AdminNavigationComposer::class);
    }
}
