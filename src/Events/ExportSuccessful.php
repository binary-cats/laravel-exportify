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
        public readonly string $filePath,
        public readonly string $disk
    ) {}

    /**
     * Get the channels the export-successful event should be broadcast
     */
    public function broadcastOn(): array
    {
        return [
            new PrivateChannel('user.'.$this->user->getAuthIdentifier()),
        ];
    }

    /**
     * Get the data to broadcast with export-successful event.
     */
    public function broadcastWith(): array
    {
        return [
            'filePath' => $this->filePath,
            'disk' => $this->disk,
        ];
    }
}
