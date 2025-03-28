<?php

namespace BinaryCats\Exportify;

use BinaryCats\Exportify\Contracts\Exportable as ExportableContract;
use BinaryCats\Exportify\Contracts\HandlesExport;
use Illuminate\Support\Traits\Tappable;
use Livewire\Wireable;

abstract class Exportable implements ExportableContract, Wireable
{
    use Tappable;

    protected ?HandlesExport $handler = null;

    public function __construct(
        private readonly ExportableConfig $config
    ) {}

    public function defaults(): array
    {
        return $this->config->defaults;
    }

    public function handler(array $arguments = []): HandlesExport
    {
        if (is_null($this->handler)) {
            $this->handler = $this->config->handler();
        }

        return $this->handler->arguments($arguments);
    }

    public function livewire(): string
    {
        return $this->config->livewire;
    }

    public function toLivewire(): array
    {
        return $this->config->toArray();
    }

    public static function fromLivewire($value)
    {
        return new static(
            config: new ExportableConfig(...$value)
        );
    }
}
