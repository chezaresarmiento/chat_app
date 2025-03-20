<?php
namespace App\Events;

use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Illuminate\Broadcasting\Channel;

class MessageSent implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $message;
    public $room;

    public function __construct($message, $room)
    {
        $this->message = $message;
        $this->room = $room;
        Log::info('MessageSent event instantiated', ['message' => $message, 'room' => $room]);
    }

    public function broadcastAs()
    {
        return 'MessageSent';
    }

    public function broadcastOn()
    {
        return new Channel($this->room); // ✅ Make sure the channel name matches
    }

    public function broadcastWith()
    {
        return [
            'message' => $this->message,
            'room' => $this->room
        ];
    }

    
}
