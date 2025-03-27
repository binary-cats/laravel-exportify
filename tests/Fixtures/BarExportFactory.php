<?php

namespace BinaryCats\Exportify\Tests\Fixtures;

use BinaryCats\Exportify\Contracts\Exportable;
use BinaryCats\Exportify\Contracts\ExportFactory;

class BarExportFactory implements ExportFactory
{
    public function exportable(array $attributes = []): Exportable
    {
        return new BarExportable;
    }

    public function defaults(): array
    {
        return [
            'bar' => true,
        ];
    }

    public function livewire(): string
    {
        return ExportableLivewireFixture::class;
    }
}
