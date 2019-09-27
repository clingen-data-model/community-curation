<?php

use App\CurationActivity;
use App\ExpertPanel;

return [
    'assignable-types' => [
        CurationActivity::class,
        ExpertPanel::class
    ],
    'assignment-statuses' => [
        'active' => 1,
        'retired' => 2
    ]
];
