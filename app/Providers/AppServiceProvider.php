<?php

namespace App\Providers;

use App\Http\View\Composers\AdminLayoutComposer;
use App\Http\View\Composers\HeaderComposer;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;
use RalphJSmit\Laravel\SEO\TagManager;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->singleton(TagManager::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        View::composer('layouts.admin', AdminLayoutComposer::class);
        View::composer('*', HeaderComposer::class);
    }
}
