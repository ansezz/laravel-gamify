<?php

namespace Ansezz\Gamify;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

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


    public function isAchieved($subject)
    {
        if (class_exists($this->class)) {
            return ((new $this->class)($this, $subject));
        }

        return config('gamify.badge_is_archived');
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
