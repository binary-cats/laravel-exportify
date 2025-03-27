<?php

namespace BinaryCats\Exportify\Contracts;

use Illuminate\Foundation\Bus\PendingDispatch;

interface Exportable
{
    /**
     * Download the export.
     *
     * @param  array<string, mixed>  $parameters
     * @return mixed
     */
    public function download(array $parameters = []): mixed;

    /**
     * Queue the export for processing.
     */
    public function queue(string $filePath, string $disk): PendingDispatch;

    /**
     * Get the raw data for the export.
     */
    public function raw(array $parameters = []): mixed;

    /**
     * Store the export.
     */
    public function store(string $filePath, string $disk): mixed;

    /**
     * Get the tags for this export.
     *
     * @return array<string>
     */
    public function tags(): array;
}
