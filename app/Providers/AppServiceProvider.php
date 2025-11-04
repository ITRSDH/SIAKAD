<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Session;

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
        // Bagikan data user ke semua view
        View::composer('*', function ($view) {
            if (Session::has('user')) {
                $view->with('user', Session::get('user'));
            } else {
                $view->with('user', null); // Opsional: jika tidak login
            }
        });
    }
}
