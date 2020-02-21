<?php

namespace App\Import\SheetHandlers;

use Box\Spout\Reader\SheetInterface;
use App\Import\Contracts\SheetHandler;

class AssignmentsSheetHandler extends AbstractSheetHandler implements SheetHandler
{
    /**
     * @var SheetHandler
     */
    private $nextHandler;

    public function handle(SheetInterface $sheet):array
    {
        if ($sheet->getName() != 'Assignments') {
            return parent::handle($sheet);
        }

        $rows = [];
        $header = null;
        foreach ($sheet->getRowIterator() as $idx => $rowObj) {
            if ($idx == 1) {
                continue;
            }
            $rowValues = rowToArray($rowObj);
            if (is_null($header)) {
                $header = array_map(function ($itm) {
                    return trim(strtolower($itm));
                }, $rowValues);
                continue;
            }

            $row = arrayTrimStrings(array_combine($header, $rowValues));
            $rows[] = [
                'name' => $row['name'],
                'email' => trim(preg_replace('/\"/', '', $row['email address'])),
                'status' => $row['status'],
                'ca_assignment_date' => $row['timestamp'],
                'ca_assignment' => $row['curation effort'],
                'ep_assignment' => $row['wg /ep'],
                'training_date' => $row['training date'],
                'training_attended' => yesNoToBool($row['training attended']),
                'attestation_signed' => yesNoToBool($row['attestation signed']),
                'volunteer_type_id' => $this->getVolunteerTypeId($row['volunteer type']),
                'notes' => $row['notes']
            ];
        }


        return collect($rows)->groupBy('email')->toArray();
    }

    private function getVolunteerTypeId($typeString)
    {
        $typeString = trim(strtolower($typeString));
        if (!$typeString) {
            return null;
        }
        if ($typeString == 'baseline') {
            return 1;
        }
        if ($typeString == 'comprehensive') {
            return 2;
        }

        throw new \Exception('Unkown volunteer type string: '.$typeString);
    }
}
