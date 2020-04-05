<?php

namespace Ansezz\Gamify;

use Ansezz\Gamify\Exceptions\LevelNotExist;
use Illuminate\Support\Str;

class BaseBadge
{

    /**
     * @param $level
     * @param $subject
     *
     * @return \Illuminate\Config\Repository|mixed
     * @throws \Ansezz\Gamify\Exceptions\LevelNotExist
     */
    public function levelIsAchieved($level, $subject)
    {
        $level = array_search($level, config('gamify.badge_levels'));

        if (!$level) {
            throw new LevelNotExist("Level [ id : $level ] must be define in gamify config file .");
        }

        $method = Str::camel($level);;

        if (method_exists($this, $method)) {
            return $this->{$method}($this, $subject);
        }

        return config('gamify.badge_is_archived');
    }
}
