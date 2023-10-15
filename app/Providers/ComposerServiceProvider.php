<?php

namespace App\Providers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class ComposerServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        View::composer('*', function ($view) {
            if (Auth::guest()) {
                $user = [
                    'roles' => [],
                    'permissions' => [],
                ];
                $view->with('user', compact('user'));

                return;
            }
        });
    }

    /**
     * Register services.
     */
    public function register(): void
    {
    }
}
