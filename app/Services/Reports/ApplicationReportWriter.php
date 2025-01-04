<?php

namespace App\Services\Reports;

use App\Contracts\ReportWriter;
use Box\Spout\Writer\Common\Creator\Style\StyleBuilder;
use Box\Spout\Writer\XLSX\Writer as XlsxWriter;
use Illuminate\Support\Collection;

class ApplicationReportWriter extends AbstractReportWriter implements ReportWriter
{
    const SHEET_NAMES = [
        'personal',
        'professional',
        'demographic',
        'outreach',
        'motivation',
        'goals',
        'interests',
        'ccdb',
    ];

    protected $writer;

    public function __construct(XlsxWriter $writer)
    {
        $this->writer = $writer;
    }

    public function writeData(Collection $data): static
    {
        $sheets = $this->initializeSheets($data->first());

        $data->each(function ($row) use ($sheets) {
            foreach ($row as $key => $values) {
                $this->setCurrentSheet($sheets[$key]);
                $this->getWriter()->addRow($this->createRow($values));
            }
        });

        return $this;
    }

    public function addMetadata($metadata): static
    {
        $sheet = $this->writer->addNewSheetAndMakeItCurrent();
        $sheet->setName('metadata');
        $this->getWriter()->addRow($this->buildHeader($metadata));
        $metadata->each(function ($row) {
            $this->getWriter()->addRow($this->createRow($row->toArray()));
        });

        return $this;
    }

    private function initializeSheets($firstRow)
    {
        $sheets = array_map(function ($sheetName) use ($firstRow) {
            $sheet = $this->writer->addNewSheetAndMakeItCurrent();
            $sheet->setName($sheetName);
            $this->getWriter()->addRow($this->buildHeader(collect([$firstRow[$sheetName]])), (new StyleBuilder())->setFontBold()->build());

            return [$sheetName, $sheet];
        }, self::SHEET_NAMES);
        
        return array_combine(array_column($sheets, 0), array_column($sheets, 1));
    }
}
