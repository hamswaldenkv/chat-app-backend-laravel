<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class MessageSent implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * @var string
     */
    protected $chat_id;

    /**
     * @var string
     */
    protected $message;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(string $chat_id, string $message)
    {
        $this->chat_id = $chat_id;
        $this->message = $message;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        $channelName = "incoming-message-{$this->chat_id}";
        return new PrivateChannel($channelName);
    }

    public function broadcastAs()
    {
        return "incoming-message-{$this->chat_id}";
    }

    /**
     * Get the data to broadcast.
     *
     * @return array<string, mixed>
     */
    public function broadcastWith(): array
    {
        return [
            'message' => $this->message
        ];
    }
}
