<?php

namespace BinaryCats\Exportify\Tests\Fixtures;

use BinaryCats\Exportify\Contracts\ExportFactory;
use BinaryCats\Exportify\Contracts\Exportable;
use BinaryCats\Exportify\Tests\Fixtures\ExportableLivewireFixture;

class FooExportFactory implements ExportFactory
{
    public function exportable(array $attributes = []): Exportable
    {
        return new FooExportable();
    }

    public function defaults(): array
    {
        return [
            'foo' => true,
        ];
    }

    public function livewire(): string
    {
        return ExportableLivewireFixture::class;
    }
} 