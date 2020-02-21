<?php

namespace App\Import\SheetHandlers;

use Box\Spout\Reader\SheetInterface;
use App\Import\Contracts\SheetHandler;

abstract class AbstractAttestationSheetHandler extends AbstractSheetHandler implements SheetHandler
{

    public function handle(SheetInterface $sheet):array
    {
        if ($sheet->getName() !== $this->sheetName) {
            return parent::handle($sheet);
        }

        $rows = $this->processRows(
                    $sheet, 
                    function ($rowNum, $rowValues) { 
                        return $this->handleRow($rowNum, $rowValues); 
                    }
                );
        $keyBy = (array_key_exists('email', $rows->first())) ? 'email' : 'name';
        return $rows->keyBy($keyBy)
                ->toArray();
    }
    
    private function handleRow($rowNum, $rowValues)
    {
        if ($rowNum == 1) {
            return null;
        }

        $rowValues = array_map(function ($value) {
            if (is_string($value) && in_array(strtolower($value), ['yes', 'no'])) {
                return (int)yesNoToBool($value);
            }
            return $value;
        }, $rowValues);
        $rowData = array_combine($this->getRowKeys(), array_pad(array_slice($rowValues, 0, count($this->getRowKeys())), count($this->getRowKeys()), null));
        $rowData['data'] = $this->getData($rowData);
        return $rowData;
    }
    
    public abstract function getRowKeys();

    public function getData($rowData) {
        $data = collect($rowData)->except('signed_at', 'name', 'signedAt', 'date')->toArray();
        return $data;
    }

}
