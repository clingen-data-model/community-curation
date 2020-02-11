<?php

namespace App\Import\SheetHandlers;

use App\Import\Contracts\SheetHandler;

class DosageAttestationHandler extends AbstractAttestationSheetHandler implements SheetHandler
{
    /**
     * @var string name of sheet
     */
    protected $sheetName = 'Dosage Attestations';

    /**
     * @var array Column names
     */
    private $rowKeys = [
        'signed_at',
        'attended_zoom_training',
        'read_dosage_eval_process',
        'name',
        'date'
    ];

    public function getRowKeys()
    {
        return $this->rowKeys;
    }    
}
