<?php

namespace BinaryCats\Exportify\Events;

use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class ExportSuccessful implements ShouldBroadcast
{
    use Dispatchable;
    use InteractsWithSockets;
    use SerializesModels;

    public function __construct(
        public readonly Authenticatable $user,
        public readonly string $exportFactory,
        public readonly string $filePath,
        public readonly string $disk
    ) {
    }

    /**
     * Get the event broadcast name
     */
    public function broadcastAs(): string
    {
        return str($this->exportFactory)
            ->append('\\Success');
    }

    /**
     * Get the channels the event should be broadcast
     */
    public function broadcastOn(): array
    {
        return [
            new PrivateChannel('user.'.$this->user->getAuthIdentifier()),
        ];
    }

    public function broadcastWith(): array
    {
        return [
            'filePath' => $this->filePath,
            'disk' => $this->disk,
        ];
    }
}
