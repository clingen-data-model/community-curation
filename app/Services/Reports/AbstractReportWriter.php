<?php

namespace App\Services\Reports;

use App\Contracts\ReportWriter;
use OpenSpout\Writer\XLSX\Writer as XLSXWriter;
use OpenSpout\Common\Entity\Row;
use OpenSpout\Common\Entity\Cell;
use OpenSpout\Writer\AbstractWriter;
use Carbon\Carbon;
use DateTime;
use Illuminate\Support\Carbon as IlluminateCarbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;

abstract class AbstractReportWriter implements ReportWriter
{
    protected $writer;

    abstract public function writeData(Collection $data): static;

    public function addMetadata(Collection $data): static
    {
        return $this;
    }

    public function setPath($path): ReportWriter
    {
        $this->getWriter()->openToFile($path);

        return $this;
    }

    public function closeWriter(): static
    {
        $this->getWriter()->close();
        return $this;
    }

    protected function arrayToCells($array)
    {
        return array_map(function ($item) {
            $value = $item;
            if (is_object($item)) {
                $value = $item->toString();
                if (in_array(get_class($item), [Carbon::class, IlluminateCarbon::class, DateTime::class])) {
                    $value = $item->format('Y-m-d');
                }
            }

            return Cell::fromValue($value);
        }, $array);
    }

    protected function buildHeader($data)
    {
        return Row::fromValues(
            collect($data->first())->keys()
                ->transform(function ($heading) {
                    return preg_replace('/_/', ' ', Str::title($heading));
                })
                ->toArray()
            );
    }

    public function getWriter(): AbstractWriter
    {
        return $this->writer;
    }

    protected function createRow($data)
    {
        return new Row($this->arrayToCells($data));
    }

    public function __call($name, $arguments)
    {
        // if (!method_exists($this, $name)) {
        // Call the method on the writer.
        if (method_exists($this->getWriter(), $name)) {
            return call_user_func_array([$this->writer, $name], $arguments);
        }
        // }
    }
}
