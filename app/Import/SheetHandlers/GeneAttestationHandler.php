<?php

namespace App\Import\SheetHandlers;

use App\Import\Contracts\SheetHandler;

class GeneAttestationHandler extends AbstractAttestationSheetHandler implements SheetHandler
{
    /**
     * @var string name of sheet
     */
    protected $sheetName = 'Gene Attestations';

    /**
     * @var array Column names
     */
    private $rowKeys = [
        'signed_at',
        'attendedTraining',
        'watchedClassificationVideo',
        'watchedScoring',
        'watchedLumpingAndSplitting',
        'reviewedPowerPoints',
        'reviewedSOP',
        'name',
        'date',
    ];

    public function getRowKeys()
    {
        return $this->rowKeys;
    }
}
