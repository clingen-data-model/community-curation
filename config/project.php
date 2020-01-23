<?php

use App\CurationActivity;
use App\ExpertPanel;

return [
    'assignable-types' => [
        CurationActivity::class,
        ExpertPanel::class
    ],
    'volunteer-statuses' => [
        'applied' => 1,
        'trained' => 2,
        'active' => 3,
        'unresponsive' => 4,
        'declined' => 5,
        'retired' => 6,
    ],
    'volunteer_types' => [
        'baseline' => 1,
        'comprehensive' => 2
    ],
    'assignment-statuses' => [
        'assigned' => 1,
        'trained' => 2,
        'active' => 3,
        'retired' => 4,
    ],
];
