<?php

namespace App\Providers;

use App\User;
use App\Attestation;
use App\ExpertPanel;
use App\WorkingGroup;
use App\Policies\UserPolicy;
use Laravel\Passport\Passport;
use App\Policies\AttestationPolicy;
use App\Policies\ExpertPanelPolicy;
use App\Policies\WorkingGroupPolicy;
use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

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
        ExpertPanel::class => ExpertPanelPolicy::class,
        Attestation::class => AttestationPolicy::class,
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        Passport::routes();
    }
}
