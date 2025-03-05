<?php

namespace App\Contracts;

use OpenSpout\Writer\AbstractWriter;
use Illuminate\Support\Collection;

interface ReportWriter
{
    public function setPath($path): ReportWriter;

    public function writeData(Collection $data): static;
    public function addMetadata(Collection $data): static;

    public function closeWriter(): static;

    // public function getCurrentSheet():Sheet;
    // public function addRow
    public function getWriter(): AbstractWriter;
}
