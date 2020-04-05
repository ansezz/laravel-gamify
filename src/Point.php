<?php

namespace Ansezz\Gamify;

use Illuminate\Database\Eloquent\Model;

class Point extends Model
{
    protected $guarded = [];

    /**
     * Point Gamify Group
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function gamifyGroup()
    {
        return $this->belongsTo(GamifyGroup::class);
    }

    public function isAchieved($subject)
    {
        if (class_exists($this->class)) {
            return ((new $this->class)($this, $subject));
        }

        return config('gamify.point_is_archived');
    }

    /**
     * @param $subject
     *
     * @return \Illuminate\Config\Repository|mixed
     */
    public function getPoints($subject)
    {
        if (class_exists($this->class)) {
            $class = new $this->class;
            if (method_exists($class, 'getDynamicPoints')) {
                return $class->getDynamicPoints($this, $subject);
            }
        }

        return $this->point;
    }
}
