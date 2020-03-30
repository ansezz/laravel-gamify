<?php

namespace Ansezz\Gamify\Traits;

use Ansezz\Gamify\Badge;

trait HasBadges
{

    /**
     * @return \Illuminate\Database\Eloquent\Relations\morphMany
     */
    public function badges()
    {
        return $this->morphToMany(Badge::class, 'badgable');
    }


    public function achieveBadge($badge)
    {
        $this->badges()->attach($badge);

        return $this;
    }


    public function undoBadge($badge)
    {
        $this->badges()->detach($badge);

        return $this;
    }

    public function syncBadges($subject = null)
    {
        $subject = is_null($subject) ? $this : $subject;

        $badgeIds = Badge::all()->filter
            ->isArchived($subject)
            ->map->id;

        $subject->badges()->sync($badgeIds);
    }
}
