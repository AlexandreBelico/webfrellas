<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Support\Facades\URL;

class JobApplyEvent implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;
    
    public $message;
    public $send_user_id;
    public $send_user_profile_pic;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($message,$send_user_id,$send_user_profile_pic)
    {
        $this->send_user_id = $send_user_id;
        $this->send_user_profile_pic = $send_user_profile_pic;
        $this->message  = "{$message}";
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return Channel|array
     */
    public function broadcastOn()
    {
        return ['job-apply-event'];
    }
}
