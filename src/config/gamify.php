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
];
