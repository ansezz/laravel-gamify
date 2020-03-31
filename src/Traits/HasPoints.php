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
        return $this->morphToMany(Point::class, 'pointable');
    }

    public function getPointSumAttribute()
    {
        return $this->points()->sum('point');
    }

    /**
     * Reset a user point to zero
     *
     * @return mixed
     */
    public function resetPoint()
    {
        $this->points()->delete();

        PointsChanged::dispatch($this, 0, false);

        return $this;
    }


    public function achievePoint($point)
    {
        $this->points()->attach($point->id);

        PointsChanged::dispatch($this, $point->point, false);

        return $this;
    }

    public function undoPoint($point)
    {
        $this->points()->detach($point);

        PointsChanged::dispatch($this, $point->point, false);

        return $this;
    }
}
