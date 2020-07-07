<?php

namespace App\Contracts;

use Illuminate\Support\Collection;
use Box\Spout\Writer\WriterAbstract;
use Box\Spout\Writer\Common\Entity\Sheet;

interface ReportWriter
{
    public function setPath($path):ReportWriter;
    public function writeData(Collection $data);
    // public function getCurrentSheet():Sheet;
    // public function addRow
    public function getWriter():WriterAbstract;
}
