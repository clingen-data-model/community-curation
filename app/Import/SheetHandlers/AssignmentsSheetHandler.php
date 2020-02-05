<?php

namespace App\Import\SheetHandlers;

use App\Import\Contracts\SheetHandler;
use Box\Spout\Writer\Common\Entity\Sheet;

class AssignmentsSheetHandler extends AbstractSheetHandler implements SheetHandler
{
    /**
     * @var SheetHandler
     */
    private $nextHandler;

    public function handle(Sheet $sheet)
    {
        if ($sheet->getName() != 'Assignments') {
            return parent::handle($sheet);
        }

        $header = null;
        foreach ($sheet->getRowIterator() as $idx => $rowObj) {
            if ($idx == 1) {
                continue;
            }
            $rowValues = array_map(function ($cell) { return $cell->getValue(); }, $rowObj->getCells());
            if (is_null($header)) {
                $header = array_map(function ($itm) {return strtolower($itm);}, $rowValues);
                continue;
            }

            $row = array_combine($header, $rowValues);
            // $volunteer = $this->createVolunteer($row);
            // $this->transcribeApplication($volunteer, $row);
            // $this->setSelfDescription($volunteer, $row);
            // $this->assignCurationActivity($volunteer, $row);
        }

    }
    
}
