<?php

namespace BinaryCats\Exportify\Concerns;

use BinaryCats\Exportify\Contracts\Exportable;
use BinaryCats\Exportify\Contracts\HandlesExport;
use BinaryCats\Exportify\Events\ExportFailed;
use BinaryCats\Exportify\Events\ExportSuccessful;
use BinaryCats\Exportify\Jobs\DispatchExportCompletedNotification;
use Illuminate\Contracts\Auth\Guard;
use Illuminate\Support\Facades\Storage;

/**
 * @mixin \Livewire\Component
 */
trait WithExportify
{
    public Exportable $exportable;


    public $exportable_disk;

    /**
     * Filtering arguments
     */
    public array $exportableArguments = [];

    /**
     * Mount the exportable
     */
    public function mountWithExportify(Exportable $exportable): void
    {
        $this->exportableArguments = $exportable->defaults();
    }

    /**
     * Subscribe to the success and failure events
     */
    public function bootWithExportify(Guard $guard): void
    {
        $this->listeners['echo:private-user.'.$guard->id().',\\'.ExportSuccessful::class] = 'handleSuccess';
        $this->listeners['echo:private-user.'.$guard->id().',\\'.ExportFailed::class] = 'handleFail';
    }

    /**
     * Default success handler
     */
    public function handleSuccess(array $event)
    {
        $this->dispatch('exportify:success', $event);

        if (method_exists($this, 'handleFinally')) {
            $this->handleFinally(true, $event);
        }

        return Storage::disk($event['disk'])
            ->download($event['filePath']);
    }

    /**
     * Default failure handler
     */
    public function handleFail(array $event)
    {
        $this->dispatch('exportify:failed', $event);

        if (method_exists($this, 'handleFinally')) {
            $this->handleFinally(false, $event);
        }
    }

    /**
     * Export the data
     */
    public function export(Guard $guard): void
    {
        tap($this->exportableHandler(), function (HandlesExport $handler) use ($guard) {

            $handler->queue(
                filePath: $filePath = $handler->fileName(),
                disk: $this->exportable_disk
            )->chain([
                new DispatchExportCompletedNotification(
                    filePath: $filePath,
                    disk: $this->exportable_disk,
                    user: $guard->user(),
                ),
            ]);
        });
    }

    /**
     * Get the exportable handler with the valida arguments
     */
    protected function exportableHandler(): HandlesExport
    {
        $arguments = $this->all();

        if (method_exists($this, 'validateExportableAttributes')) {
            $arguments = $this->validateExportableAttributes();
        }

        return $this->exportable
            ->handler($arguments);
    }
}
