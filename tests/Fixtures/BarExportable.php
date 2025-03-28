<?php

namespace BinaryCats\Exportify\Tests\Fixtures;

use BinaryCats\Exportify\Exportable;
use BinaryCats\Exportify\ExportableConfig;

class BarExportable extends Exportable
{
    public static function config(): ExportableConfig
    {
        return new ExportableConfig(
            handler: FakeExportHandler::class,
            livewire: ExportableLivewireFixture::class,
            defaults: []
        );
    }

    public function tags(): array
    {
        return ['bar', 'common'];
    }
}
