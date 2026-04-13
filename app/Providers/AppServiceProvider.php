<?php

namespace App\Providers;

use App\Http\View\Composers\AdminLayoutComposer;
use App\Http\View\Composers\HeaderComposer;
use Illuminate\Support\Facades\View;
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
        View::composer('layouts.admin', AdminLayoutComposer::class);
        View::composer('partials.header', HeaderComposer::class);
    }
}
