<?php

namespace Tests\Feature\TrainingSessions;

use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

/**
 * @group training
 * @group training-sessions
 */
class TrainingSessionTestCase extends TestCase
{
    use DatabaseTransactions;

    public function setup(): void
    {
        parent::setup();
    }
}
