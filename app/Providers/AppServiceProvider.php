<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Services\AttestationFormResolver;
use App\Contracts\AttestationFormResolver as AttestationFormResolverContract;

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

        app()->bind(\Box\Spout\Writer\XLSX\Writer::class, function () {
            return  \Box\Spout\Writer\Common\Creator\WriterEntityFactory::createXLSXWriter();
        });
    }
}
