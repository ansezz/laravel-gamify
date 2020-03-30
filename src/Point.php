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
    public function group()
    {
        return $this->belongsTo(GamifyGroup::class);
    }
}
