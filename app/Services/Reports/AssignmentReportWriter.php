<?php

namespace App\Services\Reports;

use App\Contracts\ReportWriter;
use OpenSpout\Common\Entity\Style\Style;
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
        foreach ($data as $sheetName => $sheetData) {
            $sheet = $this->getCurrentSheet();
            $sheet->setName($sheetName);

            $headerStyle = new Style();
            $headerStyle->setFontBold();
            $this->addRow($this->buildHeader($sheetData), $headerStyle);

            foreach ($sheetData->toArray() as $rowData) {
                $row = $this->createRow($rowData);
                $this->addRow($row);
            }

            $this->addNewSheetAndMakeItCurrent();
        }

        $this->getWriter()->close();

        return $this;
    }
}
