<?php

namespace App\Providers;

use App\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class ComposerServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        View::composer('*', function ($view) {
            if (Auth::guest()) {
                $user = [
                    'roles' => [],
                    'permissions' => []
                ];
                $view->with('user', compact('user'));

                return;
            }
        });
    }

    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
    }
}
