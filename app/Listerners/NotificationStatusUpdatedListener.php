<?php

namespace App\Listerners;

use App\Events\NotificationStatusUpdated;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class NotificationStatusUpdatedListener
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
     * @param  \App\Events\NotificationStatusUpdated  $event
     * @return void
     */
    public function handle(NotificationStatusUpdated $event)
    {
        //
    }
}
