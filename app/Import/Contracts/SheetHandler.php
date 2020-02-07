<?php
declare(strict_types=1);

namespace App\Import\Contracts;

use Box\Spout\Reader\SheetInterface;

interface SheetHandler
{
    public function setNext(SheetHandler $handler): SheetHandler;

    public function handle(SheetInterface $sheet): array;
}