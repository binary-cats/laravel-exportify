<?php

namespace BinaryCats\Exportify\Contracts;

interface Exportable
{
    /**
     * Get the config for this export.
     */
    public static function config(): ExportableConfig;

    /**
     * Get the default arguments for this export.
     */
    public function defaults(): array;

    /**
     * Get the handler for this export.
     */
    public function handler(array $arguments = []): HandlesExport;

    /**
     * Get the name of the Livewire component for this export.
     * The value will be used with @livewire directive.
     */
    public function livewire(): string;

    /**
     * Get the tags for this export.
     *
     * @return array<string>
     */
    public function tags(): array;
}
