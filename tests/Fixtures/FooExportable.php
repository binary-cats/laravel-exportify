<?php

namespace BinaryCats\Exportify\Tests\Fixtures;

use BinaryCats\Exportify\Exportable;
use BinaryCats\Exportify\ExportableConfig;

class FooExportable extends Exportable
{
    public static function config(): ExportableConfig
    {
        return new ExportableConfig(
            handler: FakeExportHandler::class,
            livewire: ExportableLivewireFixture::class,
            defaults: ['bar' => 'baz']
        );
    }

    public function tags(): array
    {
        return ['foo', 'common'];
    }
}
