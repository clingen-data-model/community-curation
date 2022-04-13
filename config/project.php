<?php

use App\CurationActivity;
use App\CurationGroup;
use App\Gene;

return [
    'volunteer_types' => [
        'baseline' => 1,
        'comprehensive' => 2,
    ],
    'assignable-types' => [
        CurationActivity::class,
        CurationGroup::class,
        Gene::class,
    ],
    'volunteer-statuses' => [
        'applied' => 1,
        'trained' => 2,
        'active' => 3,
        'unresponsive' => 4,
        'declined' => 5,
        'retired' => 6,
        'on-hold' => 7,
    ],
    'assignment-statuses' => [
        'assigned' => 1,
        'trained' => 2,
        'active' => 3,
        'retired' => 4,
        'unresponsive' => 5,
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
        'curation-group' => 1,
        'gene' => 2,
    ],
    'upload-categories' => [
        'resume/cv' => 1,
        'photo' => 2,
        'certification-of-biocurator-training' => 3,
        'certification-of-biocurator-trainer-status' => 4,
        'variant-curation-training-logbook' => 5,
        'employer-attestation-letter' => 6,
        'other' => 7,
        'accreditation-documents' => 8,
        'training-certificate' => 9,
    ],
];
