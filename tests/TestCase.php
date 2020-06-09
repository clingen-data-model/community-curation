<?php

namespace Tests;

use App\User;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;

    protected function createAdmin()
    {
        return factory(User::class)->create()->assignRole('admin');
    }

    protected function createProgrammer()
    {
        return factory(User::class)->create()->assignRole('programmer');
    }

    protected function createVolunteer()
    {
        return factory(User::class)->create()->assignRole('volunteer');
    }
}
