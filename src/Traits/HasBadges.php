<?php

namespace Ansezz\Gamify\Traits;

use Ansezz\Gamify\Badge;
use Gamify;

trait HasBadges
{

    /**
     * @return \Illuminate\Database\Eloquent\Relations\morphMany
     */
    public function badges()
    {
        return $this->morphToMany(Badge::class, 'badgable');
    }


    /**
     * @param null $subject
     *
     * @return $this
     */
    public function syncBadges($subject = null)
    {
        $subject = is_null($subject) ? $this : $subject;
        Gamify::syncBadges($subject);

        return $this;
    }


    /**
     * @param $badge
     * @param null $subject
     *
     * @return $this
     */
    public function syncBadge($badge, $subject = null)
    {
        $subject = is_null($subject) ? $this : $subject;
        Gamify::syncBadge($badge, $subject);

        return $this;
    }
}
