<?php
declare(strict_types=1);

namespace App\Import\Contracts;

use Box\Spout\Writer\Common\Entity\Sheet;

interface SheetHandler
{
    public function setNext(SheetHandler $handler): SheetHandler;

    public function handle(Sheet $sheet);
}