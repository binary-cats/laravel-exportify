<?php

namespace BinaryCats\Exportify\Tests\Fixtures;

use BinaryCats\Exportify\Contracts\ExportFactory;
use BinaryCats\Exportify\Contracts\Exportable;

class BazExportFactory implements ExportFactory
{
    public function exportable(array $attributes = []): Exportable
    {
        return new BazExportable();
    }

    public function defaults(): array
    {
        return [
            'baz' => true,
        ];
    }

    public function livewire(): string
    {
        return ExportableLivewireFixture::class;
    }
} 