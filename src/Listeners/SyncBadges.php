<?php

namespace Ansezz\Gamify\Listeners;

use Ansezz\Gamify\Events\PointsChanged;

class SyncBadges
{
    /**
     * Handle the event.
     *
     * @param  PointsChanged  $event
     * @return void
     */
    public function handle(PointsChanged $event)
    {
        $event->subject->syncBadges();
    }
}
