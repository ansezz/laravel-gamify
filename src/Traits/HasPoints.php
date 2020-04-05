<?php

namespace Ansezz\Gamify\Traits;

use Ansezz\Gamify\Events\PointsChanged;
use Ansezz\Gamify\Point;

trait HasPoints
{
    /**
     * @return \Illuminate\Database\Eloquent\Relations\morphMany
     */
    public function points()
    {
        return $this->morphToMany(Point::class, 'pointable')->withPivot(['achieved_points']);
    }

    public function getAchievedPointsAttribute()
    {
        return $this->points()->sum('achieved_points');
    }

    /**
     * Reset a user point to zero
     *
     * @param bool $event
     *
     * @return mixed
     */
    public function resetPoint($event = true)
    {
        $this->points()->delete();

        if ($event) {
            PointsChanged::dispatch($this, 0, false);
        }

        return $this;
    }


    /**
     * @param \Ansezz\Gamify\Point $point
     *
     * @param bool $event
     *
     * @return $this
     */
    public function achievePoint(Point $point, $event = true)
    {
        $achieved_points = $point->getPoints($this);
        $this->points()->attach([$point->id => ['achieved_points' => $achieved_points]]);

        if ($event) {
            PointsChanged::dispatch($this, $achieved_points, true);
        }

        return $this;
    }

    /**
     * @param $point
     * @param bool $event
     *
     * @return $this
     */
    public function undoPoint($point, $event = true)
    {
        $this->points()->detach($point);

        if ($event) {
            PointsChanged::dispatch($this, $point->getPoints($this), false);
        }

        return $this;
    }
}
