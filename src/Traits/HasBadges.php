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
     * @param $badge
     *
     * @return $this
     */
    public function achieveBadge($badge)
    {
        $this->badges()->attach($badge);

        return $this;
    }


    /**
     * @param $badge
     *
     * @return $this
     */
    public function undoBadge($badge)
    {
        $this->badges()->detach($badge);

        return $this;
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
}
