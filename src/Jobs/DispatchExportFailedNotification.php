<?php

namespace BinaryCats\Exportify\Jobs;

use BinaryCats\Exportify\Events\ExportFailed;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Contracts\Auth\Guard;

class DispatchExportFailedNotification
{
    public function __construct(
        public readonly string $filePath,
        public readonly string $disk,
        public readonly string $exportFactory,
        public readonly ?Authenticatable $user = null
    ) {}

    /**
     * If user is authenticated, an event will be dispatched
     */
    public function handle(Guard $guard): void
    {
        ExportFailed::dispatchUnless(
            $this->user === null,
            $this->user,
            $this->exportFactory,
            $this->filePath,
            $this->disk
        );
    }
}
