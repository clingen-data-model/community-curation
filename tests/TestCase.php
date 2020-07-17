<?php

namespace Tests;

use App\User;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;
    use DatabaseTransactions;

    private function createUser($data, $number, $roles = [])
    {
        $users = factory(User::class, $number)
            ->states($roles)
            ->create($data);
        
        if ($users->count() == 1) {
            return $users->first();
        }

        return $users;
    }
    
    protected function createAdmin($data = [], $number = 1)
    {
        return $this->createUser($data, $number, ['admin']);
    }

    protected function createProgrammer($data = [], $number = 1)
    {
        return $this->createUser($data, $number, ['programmer']);
    }

    protected function createVolunteer($data = [], $number = 1)
    {
        return $this->createUser($data, $number, ['volunteer']);
    }

    protected function expectUnauthorized()
    {
        $this->withoutExceptionHandling();
        $this->expectException(AuthorizationException::class);
    }
}
