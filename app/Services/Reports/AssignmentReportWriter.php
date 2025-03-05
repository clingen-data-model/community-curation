<?php

namespace App\Services\Reports;

use App\Contracts\ReportWriter;
use OpenSpout\Writer\XLSX\Writer as XlsxWriter;
use Illuminate\Support\Collection;

class AssignmentReportWriter extends AbstractReportWriter implements ReportWriter
{
    protected $writer;

    public function __construct(XlsxWriter $writer)
    {
        $this->writer = $writer;
    }

    public function writeData(Collection $data): static
    {
        if (!$data['all']->isEmpty()) {
            $sheet = $this->getCurrentSheet();
            foreach ($data as $sheetName => $sheetData) {
                if ($sheet->getName() !== 'Sheet1') {
                    $sheet = $this->addNewSheetAndMakeItCurrent();
                }
                $sheet->setName($sheetName);

                $this->addRow($this->buildHeader($data['all']));

                foreach ($sheetData->toArray() as $rowData) {
                    $row = $this->createRow($rowData);
                    $this->addRow($row);
                }

            }
        }

        $this->getWriter()->close();

        return $this;
    }
}
