<?php

declare(strict_types=1);

namespace App\Import\SheetHandlers;

use App\Import\Contracts\SheetHandler;
use Box\Spout\Reader\SheetInterface;

abstract class AbstractSheetHandler implements SheetHandler
{
    /**
     * @var SheetHandler
     */
    private $nextHandler;

    public function setNext(SheetHandler $handler): SheetHandler
    {
        $this->nextHandler = $handler;

        return $handler;
    }

    public function handle(SheetInterface $Sheet): array
    {
        if ($this->nextHandler) {
            return $this->nextHandler->handle($Sheet);
        }

        return [];
    }

    protected function processRows(SheetInterface $sheet, callable $rowProcessor)
    {
        $rows = collect();

        foreach ($sheet->getRowIterator() as $rowNum => $rowObj) {
            $rowValues = arrayTrimStrings(rowToArray($rowObj));
            $rows->push($rowProcessor($rowNum, $rowValues));
        }

        return $rows->filter();
    }
}
