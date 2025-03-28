<?php

namespace BinaryCats\Exportify\Events;

use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class ExportFailed implements ShouldBroadcast
{
    use Dispatchable;
    use InteractsWithSockets;
    use SerializesModels;

    public function __construct(
        public readonly Authenticatable $user,
        public readonly string $exportFactory,
    ) {}

    /**
     * Get the channels the event should be broadcast on
     */
    public function broadcastOn(): array
    {
        return [
            new PrivateChannel('user.'.$this->user->getAuthIdentifier()),
        ];
    }

    /**
     * Get the event broadcast name
     */
    public function broadcastAs(): string
    {
        return str($this->exportFactory)
            ->append('\\Fail');
    }

    /**
     * Get the data to broadcast with export-failed event.
     */
    public function broadcastWith(): array
    {
        return [
            'exportFactory' => $this->exportFactory,
        ];
    }
}
