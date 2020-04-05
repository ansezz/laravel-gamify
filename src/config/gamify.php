<?php

return [
    // Reputation model
    'point_model'                  => '\Ansezz\Gamify\Point',

    // Broadcast on private channel
    'broadcast_on_private_channel' => true,

    // Channel name prefix, user id will be suffixed
    'channel_name'                 => 'user.reputation.',

    // Badge model
    'badge_model'                  => '\Ansezz\Gamify\Badge',

    // Where all badges icon stored
    'badge_icon_folder'            => 'images/badges/',

    // Extention of badge icons
    'badge_icon_extension'         => '.svg',

    // All the levels for badge
    'badge_levels'                 => [
        'beginner'     => 1,
        'intermediate' => 2,
        'advanced'     => 3,
    ],

    // Default level
    'badge_default_level'          => 1,

    // Badge achieved vy default if check function not exit
    'badge_is_archived'            => false,

    // point achieved vy default if check function not exit
    'point_is_archived'            => true,
];
