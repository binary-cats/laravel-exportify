<?php

namespace BinaryCats\Exportify\Contracts;

interface Exportable
{
    /**
     * Download the export.
     *
     * @param  array<string, mixed>  $parameters
     */
    public function download(array $parameters = []): mixed;

    /**
     * Queue the export for processing.
     *
     * @param  array<string, mixed>  $parameters
     */
    public function queue(array $parameters = []): mixed;

    /**
     * Get the raw data for the export.
     *
     * @param  array<string, mixed>  $parameters
     */
    public function raw(array $parameters = []): mixed;

    /**
     * Store the export.
     *
     * @param  array<string, mixed>  $parameters
     */
    public function store(array $parameters = []): mixed;

    /**
     * Get the tags for this export.
     *
     * @return array<string>
     */
    public function tags(): array;
}
