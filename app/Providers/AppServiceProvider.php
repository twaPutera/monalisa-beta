<?php

namespace App\Providers;

use Firebase\JWT\JWT;
use Illuminate\Support\Facades\View;
use Illuminate\View\View as ViewView;
use Illuminate\Support\ServiceProvider;
use App\Helpers\SsoHelpers;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        JWT::$leeway = 5;
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        view::composer('*', function (ViewView $view) {

            $user = SsoHelpers::getUserLogin();
            $view->with('user', $user);
        });
    }
}
