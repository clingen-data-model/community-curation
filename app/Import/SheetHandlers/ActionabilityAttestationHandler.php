<?php

namespace App\Import\SheetHandlers;

class ActionabilityAttestationHandler extends AbstractAttestationSheetHandler
{
    /**
     * @var string name of sheet
     */
    protected $sheetName = 'Actionability Attestations';

    /**
     * @var array Column names
     */
    private $rowKeys = [
        'signedAt',
        'readIntroduction',
        'readRuleOut',
        'completedExamples',
        'attendedWebinar',
        'name',
    ];

    public function getRowKeys()
    {
        return $this->rowKeys;
    }
}
