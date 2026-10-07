<?php

namespace App\Events;

use App\Models\SupportTicket;
use App\Models\User;
use Illuminate\Broadcasting\InteractsWithBroadcasting;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class TicketStatusChanged
{
    use Dispatchable, SerializesModels, InteractsWithBroadcasting;

    /**
     * Create a new event instance.
     */
    public function __construct(
        public SupportTicket $ticket,
        public string $oldStatus,
        public string $newStatus,
        public User $originatorUser,
    ) {
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<int, \Illuminate\Broadcasting\Channel>
     */
    public function broadcastOn(): array
    {
        return [
            new PrivateChannel('channel-name'),
        ];
    }

    /**
     * Determine if this event should be queued.
     */
    public function shouldQueue(): bool
    {
        return true;
    }
}
