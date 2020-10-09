<?php

namespace App\Import\SheetHandlers;

class VariantAttestationSheetHandler extends AbstractAttestationSheetHandler
{
    public $sheetName = 'Variant Attestations';

    private $keys = [
        'signed_at',
        'name',
        'email',
    ];

    public function getRowKeys()
    {
        return $this->keys;
    }

    public function getData($rowData)
    {
        return [
            'readGuidelines' => 1,
            'readSOP' => 1,
            'reviewedSviRecommendations' => 1,
            'watchedLiteratureTraining' => 1,
            'watchedAlleleVideo' => 1,
            'attendedTraining' => 1,
        ];
    }
}
