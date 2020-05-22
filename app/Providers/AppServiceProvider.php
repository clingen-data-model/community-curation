<?php

namespace App\Providers;

use Illuminate\Support\Facades\URL;
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
        if ($this->app->environment('production')) {
            config(['backpack.base.skin'=>'skin-blue']);
        }
        if ($this->app->environment('local', 'demo')) {
            config(['backpack.base.logo_lg' => '<b>ClinGen</b> - '.$this->app->environment()]);
        }

        if (config('app.url_scheme')) {
            URL::forceScheme('http');
        }
        
     
        app()->bind(AttestationFormResolverContract::class, AttestationFormResolver::class);

        app()->bind(\Box\Spout\Writer\XLSX\Writer::class, function () {
            return  \Box\Spout\Writer\Common\Creator\WriterEntityFactory::createXLSXWriter();
        });
    }
}
