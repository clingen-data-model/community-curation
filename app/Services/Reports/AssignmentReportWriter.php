<?php

namespace App\Services\Reports;

use App\Contracts\ReportWriter;
use Box\Spout\Writer\Common\Creator\Style\StyleBuilder;
use Box\Spout\Writer\XLSX\Writer as XlsxWriter;
use Illuminate\Support\Collection;

class AssignmentReportWriter extends AbstractReportWriter implements ReportWriter
{
    protected $writer;

    public function __construct(XlsxWriter $writer)
    {
        $this->writer = $writer;
    }

    public function writeData(Collection $data)
    {
        foreach ($data as $sheetName => $sheetData) {
            $sheet = $this->getCurrentSheet();
            $sheet->setName($sheetName);

            $this->addRow($this->buildHeader($sheetData), (new StyleBuilder())->setFontBold()->build());

            foreach ($sheetData->toArray() as $rowData) {
                $row = $this->createRow($rowData);
                $this->addRow($row);
            }

            $this->addNewSheetAndMakeItCurrent();
        }

        $this->getWriter()->close();
    }
}
