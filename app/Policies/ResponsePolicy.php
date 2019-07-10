<?php

namespace App\Policies;

use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use Sirs\Surveys\Contracts\SurveyResponse as Response;

class ResponsePolicy
{
    use HandlesAuthorization;

    /**
     * Create a new policy instance.
     *
     * @return void
     */
    public function __construct()
    {
    }

    public function editFinalized(User $user, Response $response)
    {
        return config('surveys.editAfterFinalized', true);
    }

    public function edit(User $user, Response $response)
    {
        return true;
    }

    public function view(User $user, Response $response)
    {
        return true;
    }
}
