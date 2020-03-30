<?php

namespace Ansezz\Gamify;

use Illuminate\Database\Eloquent\Model;

class GamifyLevel extends Model
{
    protected $guarded = [];

    /**
     * Level Badges
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function badges()
    {
        return $this->hasMany(Badge::class);
    }
}
