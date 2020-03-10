<?php

use App\Gene;
use App\ExpertPanel;
use App\CurationActivity;

return [
    'assignable-types' => [
        CurationActivity::class,
        ExpertPanel::class,
        Gene::class,
    ],
    'assignment-statuses' => [
        'assigned' => 1,
        'trained' => 2,
        'active' => 3,
        'retired' => 4,
    ],
    'curation-activity-types' => [
        'expert-panel' => 1,
        'gene' => 2
    ]
];
