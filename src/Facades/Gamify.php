<?php

namespace Ansezz\Gamify\Facades;

use Ansezz\Gamify\Badge;

class Gamify
{

    /**
     * @param $subject
     *
     * @return bool
     */
    public function syncBadges($subject)
    {
        $badgeIds = Badge::all()->filter
            ->isAchieved($subject)
            ->map->id;

        return $subject->badges()->sync($badgeIds);
    }
}

