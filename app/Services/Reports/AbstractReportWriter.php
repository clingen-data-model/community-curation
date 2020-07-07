<?php

namespace App\Services\Reports;

use DateTime;
use Carbon\Carbon;
use Illuminate\Support\Str;
use App\Contracts\ReportWriter;
use Illuminate\Support\Collection;
use Box\Spout\Writer\XLSX\Writer as XlsxWriter;
use Illuminate\Support\Carbon as IlluminateCarbon;
use Box\Spout\Writer\Common\Creator\Style\StyleBuilder;
use Box\Spout\Writer\Common\Creator\WriterEntityFactory;
use Box\Spout\Writer\WriterAbstract;

abstract class AbstractReportWriter implements ReportWriter
{
    protected $writer;

    abstract public function writeData(Collection $data);

    public function setPath($path):ReportWriter
    {
        $this->getWriter()->openToFile($path);
        return $this;
    }


    protected function arrayToCells(array $array)
    {
        return array_map(function ($item) {
            $value = $item;
            if (is_object($item)) {
                $value = $item->toString();
                if (in_array(get_class($item), [Carbon::class, IlluminateCarbon::class, DateTime::class])) {
                    $value = $item->format("Y-m-d");
                }
            }
            return WriterEntityFactory::createCell($value);
        }, $array);
    }

    protected function getHeaderCells($data)
    {
        return collect($data->first())->keys()
            ->transform(function ($heading) {
                return WriterEntityFactory::createCell(preg_replace('/_/', ' ', Str::title($heading)));
            })
            ->toArray();
    }

    protected function buildHeader($data)
    {
        return WriterEntityFactory::createRow($this->getHeaderCells($data));
    }

    public function getWriter():WriterAbstract
    {
        return $this->writer;
    }

    // protected function addRow($data, $rowStyle = null)
    // {
    //     return $this->getWriter()->addRow($data, $rowStyle);
    // }

    protected function createRow($data)
    {
        return WriterEntityFactory::createRow($this->arrayToCells($data));
    }

    // protected function addNewSheetAndMakeItCurrent()
    // {
    //     $this->getWriter()->addNewSheetAndMakeItCurrent();
    // }

    public function __call($name, $arguments)
    {
        // if (!method_exists($this, $name)) {
        // Call the method on the writer.
        if (method_exists($this->getWriter(), $name)) {
            return call_user_func_array([$this->writer,$name], $arguments);
        }
        // }
    }
}
