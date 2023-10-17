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
     */
    public function boot(): void
    {
   #     Passport::routes();
    }
}
