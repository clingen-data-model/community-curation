<?php

namespace App\Listeners\Volunteers;

use App\Events\Volunteers\ConvertedToComprehensive;
use App\Events\VolunteersConvertedToComprehensive;
use App\Notifications\ConversionToComprehensive;

class NotifyConversionToComprehensive
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
    }

    /**
     * Handle the event.
     *
     * @param  VolunteersConvertedToComprehensive  $event
     * @return void
     */
    public function handle(ConvertedToComprehensive $event): void
    {
        $event->volunteer->notify(new ConversionToComprehensive($event->volunteer));
    }
}
