<?php

namespace App\Listeners\Volunteers;

use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Events\VolunteersConvertedToComprehensive;
use App\Events\Volunteers\ConvertedToComprehensive;
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
        //
    }

    /**
     * Handle the event.
     *
     * @param  VolunteersConvertedToComprehensive  $event
     * @return void
     */
    public function handle(ConvertedToComprehensive $event)
    {
        $event->volunteer->notify(new ConversionToComprehensive($event->volunteer));
    }
}
