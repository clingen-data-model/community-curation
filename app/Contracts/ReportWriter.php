<?php

namespace App\Contracts;

use Box\Spout\Writer\Common\Entity\Sheet;
use Box\Spout\Writer\WriterAbstract;
use Illuminate\Support\Collection;

interface ReportWriter
{
    public function setPath($path): ReportWriter;

    public function writeData(Collection $data);

    // public function getCurrentSheet():Sheet;
    // public function addRow
    public function getWriter(): WriterAbstract;
}
