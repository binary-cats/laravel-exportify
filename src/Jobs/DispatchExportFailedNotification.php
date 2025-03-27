<?php

namespace BinaryCats\Exportify\Jobs;

use BinaryCats\Exportify\Events\ExportFailed;
use Illuminate\Contracts\Auth\Guard;

class DispatchExportFailedNotification
{
    public function __construct(
        public readonly string $exportFactory,
        public readonly string $filePath,
        public readonly string $disk
    ) {
    }

    /**
     * If user is authenticated, an event will be dispatched
     */
    public function handle(Guard $guard): void
    {
        ExportFailed::dispatchIf($guard->check(),
            $guard->user(),
            $this->exportFactory,
            $this->filePath,
            $this->disk
        );
    }
}
