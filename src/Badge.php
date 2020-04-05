<?php

namespace Ansezz\Gamify;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Badge extends Model
{
    protected $guarded = ['id', 'created_at', 'updated_at'];

    /**
     * @param $subject
     *
     * @return \Illuminate\Config\Repository|mixed
     */
    public function isAchieved($subject)
    {
        if (class_exists($this->class)) {
            return (new $this->class)->levelIsAchieved($this->level, $subject);
        }

        return config('gamify.badge_is_archived');
    }


    /**
     * Point Gamify Group
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function gamifyGroup()
    {
        return $this->belongsTo(GamifyGroup::class);
    }

    public function getImageAttribute($value)
    {
        if (!empty($value)) {
            return $value;
        }

        return $this->getDefaultIcon();
    }

    /**
     * Get the default icon if not provided
     *
     * @return string
     */
    protected function getDefaultIcon()
    {
        return sprintf(
            '%s/%s%s',
            rtrim(config('gamify.badge_icon_folder', 'images/badges'), '/'),
            Str::kebab($this->name),
            config('gamify.badge_icon_extension', '.svg')
        );
    }
}
