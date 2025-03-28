<?php

use BinaryCats\Exportify\ExportableConfig;
use BinaryCats\Exportify\Tests\Fixtures\ExportableLivewireFixture;
use BinaryCats\Exportify\Tests\Fixtures\FakeExportHandler;

it('will_create_config_with_valid_data', function (): void {
    $config = new ExportableConfig(
        handler: FakeExportHandler::class,
        livewire: ExportableLivewireFixture::class,
        defaults: ['foo' => 'bar']
    );

    expect($config)
        ->handler->toBe(FakeExportHandler::class)
        ->livewire->toBe(ExportableLivewireFixture::class)
        ->defaults->toBe(['foo' => 'bar']);
});

it('will_create_config_with_minimal_data', function (): void {
    $config = new ExportableConfig(
        handler: FakeExportHandler::class,
        livewire: ExportableLivewireFixture::class,
    );

    expect($config)
        ->handler->toBe(FakeExportHandler::class)
        ->livewire->toBe(ExportableLivewireFixture::class)
        ->defaults->toBe([]);
});