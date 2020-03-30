<?php

namespace Ansezz\Gamify;

use Illuminate\Database\Eloquent\Model;

class Badge extends Model
{
    protected $guarded = ['id', 'created_at', 'updated_at'];

    /**
     * Award badge to a user
     *
     * @param $user
     */
    public function awardTo($user)
    {
        // $this->users()->attach($user);
    }

    /**
     * Remove badge from user
     *
     * @param $user
     */
    public function removeFrom($user)
    {
        // $this->users()->detach($user);
    }


    public function isArchived($subject)
    {
        if (class_exists($this->class)) {
            return ((new $this->class)($this, $subject));
        }

        return false;
    }


    /**
     * Point Gamify Group
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function group()
    {
        return $this->belongsTo(GamifyGroup::class);
    }

    /**
     * Point Gamify Group
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function level()
    {
        return $this->belongsTo(GamifyLevel::class);
    }
}
