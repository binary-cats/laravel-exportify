<?php

namespace BinaryCats\Exportify\Jobs;

use BinaryCats\Exportify\Events\ExportSuccessful;
use Illuminate\Contracts\Auth\Guard;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Queue\SerializesModels;

class DispatchExportCompletedNotification
{
    use Queueable;
    use SerializesModels;

    public function __construct(
        public readonly string $exportFactory,
        public readonly string $filePath,
        public readonly string $disk
    ) {}

    /**
     * If user is authenticated, an event will be dispatched
     */
    public function handle(Guard $guard): void
    {
        ExportSuccessful::dispatchIf(
            $guard->check(),
            $guard->user(),
            $this->exportFactory,
            $this->filePath,
            $this->disk
        );
    }
}
