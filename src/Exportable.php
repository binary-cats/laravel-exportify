<?php

namespace BinaryCats\Exportify;

use BinaryCats\Exportify\Contracts\Exportable as ExportableContract;
use BinaryCats\Exportify\Contracts\HandlesExport;
use Illuminate\Support\Traits\Tappable;
use Livewire\Wireable;

abstract class Exportable implements ExportableContract, Wireable
{
    use Tappable;

    public function __construct(
        private readonly ExportableConfig $config
    ) {}

    /**
     * Statically access the exportable.
     */
    public static function make(): static
    {
        return app(static::class, ['config' => static::config()]);
    }

    /**
     * Get the default arguments for the exportable.
     */
    public function defaults(): array
    {
        return $this->config->defaults;
    }

    /**
     * Get the handler for the exportable.
     */
    public function handler(array $arguments = []): HandlesExport
    {
        return $this->config->handler($arguments);
    }

    /**
     * Get the livewire component for the exportable.
     */
    public function livewire(): string
    {
        return $this->config->livewire;
    }

    /**
     * Convert the exportable to a livewire.
     */
    public function toLivewire(): array
    {
        return $this->config->toArray();
    }

    /**
     * Convert the livewire back to an exportable.
     */
    public static function fromLivewire($value)
    {
        return new static(
            config: new ExportableConfig(...$value)
        );
    }
}
