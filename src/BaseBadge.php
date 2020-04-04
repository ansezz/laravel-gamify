<?php

namespace Ansezz\Gamify;

use Illuminate\Support\Str;

class BaseBadge
{

    public function levelIsAchieved($level, $subject)
    {
        $method = Str::camel($level->name);

        if (method_exists($this, $method)) {
            return $this->{$method}($this, $subject);
        }

        return config('gamify.badge_is_archived');
    }
}
