<?php

namespace App\Providers;

use App\Contracts\AttestationFormResolver as AttestationFormResolverContract;
use App\Services\AttestationFormResolver;
use Illuminate\Support\ServiceProvider;

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
     */
    public function boot()
    {
        app()->bind(AttestationFormResolverContract::class, AttestationFormResolver::class);
    }
}
