<?php

namespace Ansezz\Gamify\Events;

use Ansezz\Gamify\Badge;
use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class BadgeAchieved implements ShouldBroadcast
{
    use Dispatchable, SerializesModels;

    /**
     * @var Model
     */
    public $subject;

    /**
     * @var Model
     */
    public $badge;

    /**
     * Create a new event instance.
     *
     * @param \Illuminate\Database\Eloquent\Model $subject
     * @param $badge
     */
    public function __construct(Model $subject, $badge)
    {
        $this->subject = $subject;
        $this->badge = $badge;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|\Illuminate\Broadcasting\Channel[]
     */
    public function broadcastOn()
    {
        $channelName = config('gamify.channel_name') . $this->subject->getKey();

        if (config('gamify.broadcast_on_private_channel')) {
            return new PrivateChannel($channelName);
        }

        return new Channel($channelName);
    }
}
