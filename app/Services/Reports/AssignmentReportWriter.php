<?php

namespace App\Services\Reports;

use DateTime;
use Carbon\Carbon;
use Illuminate\Support\Str;
use App\Contracts\ReportWriter;
use Illuminate\Support\Collection;
use App\Services\Reports\AbstractReportWriter;
use Box\Spout\Writer\XLSX\Writer as XlsxWriter;
use Illuminate\Support\Carbon as IlluminateCarbon;
use Box\Spout\Writer\Common\Creator\Style\StyleBuilder;
use Box\Spout\Writer\Common\Creator\WriterEntityFactory;

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

            $this->addRow($this->buildHeader($data), (new StyleBuilder())->setFontBold()->build());

            foreach ($sheetData->toArray() as $rowData) {
                $row = $this->createRow($rowData);
                $this->addRow($row);
            }

            $this->addNewSheetAndMakeItCurrent();
        }

        $this->getWriter()->close();
    }
}
