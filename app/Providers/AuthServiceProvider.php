<?php

namespace App\Providers;

use App\Attestation;
use App\CurationGroup;
use App\Policies\AttestationPolicy;
use App\Policies\CurationGroupPolicy;
use App\Policies\UserPolicy;
use App\Policies\WorkingGroupPolicy;
use App\User;
use App\WorkingGroup;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Laravel\Passport\Passport;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        User::class => UserPolicy::class,
        WorkingGroup::class => WorkingGroupPolicy::class,
        CurationGroup::class => CurationGroupPolicy::class,
        Attestation::class => AttestationPolicy::class,
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function register()
    {
        parent::register();

        // Configure these before Passport's provider boots and registers routes.
        Passport::$clientUuids = false;
        Passport::$registersJsonApiRoutes = true;
        Passport::$deviceCodeGrantEnabled = false;
        Passport::useClientModel(\App\Auth\PassportClient::class);
    }

    public function boot()
    {
        $this->registerPolicies();

        // Passport 12 disables the previously available password grant by default.
        Passport::enablePasswordGrant();
        Passport::authorizationView('auth.oauth.authorize');

        // Existing clients still have Passport 12 secrets; do not rewrite credentials.
        $this->app->bind(
            \Laravel\Passport\Bridge\ClientRepository::class,
            \App\Auth\PassportClientRepository::class
        );
    }
}
