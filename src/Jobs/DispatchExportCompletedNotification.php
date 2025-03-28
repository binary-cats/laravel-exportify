<?php

namespace BinaryCats\Exportify\Jobs;

use BinaryCats\Exportify\Events\ExportSuccessful;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Queue\SerializesModels;

class DispatchExportCompletedNotification
{
    use Queueable;
    use SerializesModels;

    public function __construct(
        public readonly string $filePath,
        public readonly string $disk,
        public readonly ?Authenticatable $user = null
    ) {}

    /**
     * If user is authenticated, an event will be dispatched
     */
    public function handle(): void
    {
        ExportSuccessful::dispatchUnless(
            $this->user === null,
            $this->user,
            $this->filePath,
            $this->disk
        );
    }
}
