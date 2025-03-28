<?php

namespace BinaryCats\Exportify\Concerns;

use BinaryCats\Exportify\Contracts\Exportable;
use BinaryCats\Exportify\Events\ExportFailed;
use BinaryCats\Exportify\Events\ExportSuccessful;
use BinaryCats\Exportify\Jobs\DispatchExportCompletedNotification;
use Illuminate\Contracts\Auth\Guard;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

/**
 * @mixin \Livewire\Component
 */
trait WithExportify
{
    /**
     * Boot the trait
     */
    public function bootWithExportify(Guard $guard): void
    {
        $this->listeners['echo:private-user.'.$guard->id().',\\'.ExportSuccessful::class] = 'handleSuccess';
        $this->listeners['echo:private-user.'.$guard->id().',\\'.ExportFailed::class] = 'handleFail';
    }

    public function handleSuccess(array $event)
    {
        return Storage::disk($event['disk'])
            ->download($event['filePath']);
    }

    public function download(Guard $guard): void
    {
        $this->exportable()
            ->tap(function (Exportable $export) {
                $filePath = Str::uuid().'/'.$export->fileName();

                $export->queue(
                    filePath: $filePath,
                    disk: $this->disk
                )->chain([
                    new DispatchExportCompletedNotification(
                        $this->exportFactory,
                        $filePath,
                        $this->disk
                    ),
                ]);
            });
    }
}
