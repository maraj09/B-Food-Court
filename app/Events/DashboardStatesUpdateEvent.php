<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class DashboardStatesUpdateEvent implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $data = [];
    public $vendorUserId;
    /**
     * Create a new event instance.
     */
    public function __construct($type, $data, $vendorUserId = null)
    {
        $this->data = [
            'type' => $type,
            'data' => $data
        ];
        $this->vendorUserId = $vendorUserId;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<int, \Illuminate\Broadcasting\Channel>
     */
    public function broadcastOn(): array
    {
        if ($this->vendorUserId) {
            return [
                new PrivateChannel('dashboard-states.' . $this->vendorUserId),
            ];
        }
        return [
            new Channel('dashboard-states'),
        ];
    }

    public function broadcastWith()
    {
        return $this->data;
    }
}
