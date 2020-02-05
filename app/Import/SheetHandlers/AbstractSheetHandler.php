<?php
declare(strict_types=1);

namespace App\Import\SheetHandlers;

use App\Import\Contracts\SheetHandler;
use Box\Spout\Writer\Common\Entity\Sheet;

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

    public function handle(Sheet $Sheet)
    {
        if ($this->nextHandler) {
            return $this->nextHandler->handle($Sheet);
        }
        return;
    }
}
