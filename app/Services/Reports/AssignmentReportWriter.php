<?php

namespace App\Services\Reports;

use DateTime;
use Carbon\Carbon;
use Box\Spout\Writer\XLSX\Writer as XlsxWriter;
use Box\Spout\Writer\Common\Creator\Style\StyleBuilder;
use Box\Spout\Writer\Common\Creator\WriterEntityFactory;

class AssignmentReportWriter
{
    protected $xlsxWriter;

    public function __construct(XlsxWriter $writer)
    {
        $this->xlsxWriter = $writer;
    }

    public function setPath($path):AssignmentReportWriter
    {
        $this->xlsxWriter->openToFile($path);
        return $this;
    }

    public function writeData($data)
    {
        $headerStyle = $rowStyle = (new StyleBuilder())
            ->setFontBold()
            ->build();

        $headerRow = WriterEntityFactory::createRow($this->getHeaderCells($data->first()));

        foreach ($data as $sheetName => $sheetData) {
            $sheet = $this->xlsxWriter->getCurrentSheet();
            $sheet->setName($sheetName);

            $this->xlsxWriter->addRow($headerRow, $rowStyle);

            foreach ($sheetData->toArray() as $rowData) {
                $row = WriterEntityFactory::createRow($this->arrayToCells($rowData));
                $this->xlsxWriter->addRow($row);
            }

            $this->xlsxWriter->addNewSheetAndMakeItCurrent();
        }

        $this->xlsxWriter->close();        
    }
    private function arrayToCells(array $array) 
    {
        return array_map(function ($item) {
            $value = $item;
            if (is_object($item)) {
                $value = $item->toString();
                if (get_class($item) == Carbon::class) {
                    $value = $item->format("Y-m-d");
                } else if (get_class($item) == DateTime::class) {
                    $value = $item->format('Y-m-d');
                }
            }
            return WriterEntityFactory::createCell($value);
        }, $array);

    }

    private function getHeaderCells($data)
    {
        return collect($data->first())->keys()
            ->transform(function ($heading) {
                return WriterEntityFactory::createCell(preg_replace('/_/', ' ', title_case($heading)));
            })
            ->toArray();
    }
    
}
