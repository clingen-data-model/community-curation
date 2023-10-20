<?php

namespace App\Providers;

use Illuminate\Support\Facades\Auth;
use App\Contracts\AttestationFormResolver as AttestationFormResolverContract;
use App\Http\Requests\Contracts\VolunteerRequestContract;
use App\Http\Requests\VolunteerAdminRequest;
use App\Http\Requests\VolunteerRequest;
use App\Services\AttestationFormResolver;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\ServiceProvider;
use Lorisleiva\Actions\Facades\Actions;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Blade::withoutComponentTags();

        Actions::registerCommands('app/Actions');

        if ($this->app->environment('production')) {
            config(['backpack.base.skin' => 'skin-blue']);
        }
        if ($this->app->environment('local', 'demo')) {
            config(['backpack.base.logo_lg' => '<b>ClinGen</b> - '.$this->app->environment()]);
        }

        if (config('app.url_scheme')) {
            URL::forceScheme(config('app.url_scheme'));
        }

        app()->bind(AttestationFormResolverContract::class, AttestationFormResolver::class);

        app()->bind(\Box\Spout\Writer\XLSX\Writer::class, function () {
            return \Box\Spout\Writer\Common\Creator\WriterEntityFactory::createXLSXWriter();
        });

        app()->bind(VolunteerRequestContract::class, function () {
            if (Auth::user() && Auth::user()->isAdminOrHigher()) {
                return $this->app->make(VolunteerAdminRequest::class);
            }

            return $this->app->make(VolunteerRequest::class);
        });
    }
}
