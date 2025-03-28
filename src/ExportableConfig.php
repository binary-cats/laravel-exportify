<?php

namespace BinaryCats\Exportify;

use BinaryCats\Exportify\Contracts\ExportableConfig as ExportableConfigContract;
use BinaryCats\Exportify\Contracts\HandlesExport;
use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Contracts\Support\Arrayable;

final class ExportableConfig implements Arrayable, ExportableConfigContract
{
    public function __construct(
        public readonly string $handler,
        public readonly string $livewire,
        public readonly array $defaults = []
    ) {}

    /**
     * Get the handler for this export.
     */
    public function handler(array $arguments = []): HandlesExport
    {
        return $this->newHandler()
            ->arguments($arguments);
    }

    /**
     * Get a new handler for this export.
     *
     * @throws BindingResolutionException
     */
    public function newHandler(): HandlesExport
    {
        return app($this->handler)
            ->arguments($this->defaults);
    }

    /*
     * Convert the config to an array.
     */
    public function toArray(): array
    {
        return [
            'handler' => $this->handler,
            'livewire' => $this->livewire,
            'defaults' => $this->defaults,
        ];
    }
}
