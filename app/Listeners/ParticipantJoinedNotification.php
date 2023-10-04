<?php

namespace App\Listeners;

use App\Events\ParticipantJoined;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class ParticipantJoinedNotification
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
     * @param  \App\Events\ParticipantJoined  $event
     * @return void
     */
    public function handle(ParticipantJoined $event)
    {
        //
    }
}
