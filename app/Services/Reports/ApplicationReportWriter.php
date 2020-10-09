<?php

namespace App\Services\Reports;

use App\Contracts\ReportWriter;
use Box\Spout\Writer\Common\Creator\Style\StyleBuilder;
use Box\Spout\Writer\XLSX\Writer as XlsxWriter;
use Illuminate\Support\Collection;

class ApplicationReportWriter extends AbstractReportWriter implements ReportWriter
{
    protected $writer;

    public function __construct(XlsxWriter $writer)
    {
        $this->writer = $writer;
    }

    public function writeData(Collection $data)
    {
        $sheetNames = $data->keys();
        foreach ($sheetNames as $idx => $sheetName) {
            $sheetData = $data->get($sheetName);
            // dd($sheetData);
            $sheet = $this->getCurrentSheet();
            $sheet->setName($sheetName);

            $this->getWriter()->addRow($this->buildHeader($sheetData), (new StyleBuilder())->setFontBold()->build());

            foreach ($sheetData->toArray() as $rowData) {
                $row = $this->createRow($rowData);
                $this->getWriter()->addRow($row);
            }

            if ($idx + 1 < $sheetNames->count()) {
                $this->addNewSheetAndMakeItCurrent();
            }
        }

        $this->getWriter()->close();
    }
}
