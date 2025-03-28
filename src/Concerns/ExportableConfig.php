<?php

namespace BinaryCats\Exportify\Concerns;

use BinaryCats\Exportify\Contracts\HandlesExport;
use Livewire\Component;
use Webmozart\Assert\Assert;

final readonly class ExportableConfig
{
    public function __construct(
        public readonly string $handler,
        public readonly array $defaults,
        public readonly string $livewire,
    ) {
        Assert::classExists($this->handler, 'The exportable class does not exist');
        Assert::implementsInterface($this->handler, HandlesExport::class, 'The exportable class must implement HandlesExport interface');
        Assert::isArray($this->defaults, 'The defaults must be an array');
        Assert::classExists($this->livewire, 'The Livewire component class does not exist');
        Assert::isInstanceOf($this->livewire, Component::class, 'The Livewire component class must extend the Component class');
    }

    public function handler(array $arguments = []): HandlesExport
    {
        return $this->newExportHandler()
            ->arguments($arguments);
    }

    public function defaults(): array
    {
        return $this->defaults;
    }

    public function livewire(): string
    {
        return $this->livewire;
    }

    public function newExportHandler(): HandlesExport
    {
        return app($this->handler)
            ->arguments($this->defaults());
    }
}
