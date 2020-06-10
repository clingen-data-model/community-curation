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
    ],
    'upload-categories' => [
        'resume/cv' => 1,
        'photo' => 2,
        'certification-of-biocurator-training' => 3,
        'certification-of-biocurator-trainer-status' => 4,
        'variant-curation-training-logbook' => 5,
        'employer-attestation-letter' => 6,
        'other' => 7
    ],
];
