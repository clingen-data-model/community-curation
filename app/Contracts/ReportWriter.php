<?php

namespace App\Contracts;

use Box\Spout\Writer\Common\Entity\Sheet;
use Box\Spout\Writer\WriterAbstract;
use Illuminate\Support\Collection;

interface ReportWriter
{
    public function setPath($path): ReportWriter;

    public function writeData(Collection $data): static;
    public function addMetadata(Collection $data): static;

    public function closeWriter(): static;

    // public function getCurrentSheet():Sheet;
    // public function addRow
    public function getWriter(): WriterAbstract;
}
