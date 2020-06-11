<?php

use App\Gene;
use App\ExpertPanel;
use App\CurationActivity;

return [
    'aptitudes' => [
        'actionability-basic' => 1,
        'dosage-basic' => 2,
        'gene-basic' => 3,
        'somantic-variant-basic' => 4,
        'variant-basic' => 5,
        'variant-proficiency' => 6,
        'baseline-basic-evidence' => 7,
        'baseline-genetic-evidence' => 8,
    ],
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
    'curation-activities' => [
        'actionability' => 1,
        'dosage' => 2,
        'gene' => 3,
        'somatic-variant' => 4,
        'variant' => 5,
        'baseline' => 6,
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
