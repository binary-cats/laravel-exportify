<?php

use BinaryCats\Exportify\Concerns\ExportFactoryConfig;
use BinaryCats\Exportify\Contracts\Exportable;
use BinaryCats\Exportify\ExportFactory;
use BinaryCats\Exportify\Tests\Fixtures\ExportableLivewireFixture;
use BinaryCats\Exportify\Tests\Fixtures\FooExportable;

beforeEach(function () {
    $this->config = new ExportFactoryConfig([
        'exportable' => FooExportable::class,
        'livewire' => ExportableLivewireFixture::class,
        'defaults' => ['foo' => 'bar'],
    ]);

    $this->factory = new ExportFactory($this->config);
});

it('will_implement_export_factory_interface', function (): void {
    expect($this->factory)->toBeInstanceOf(\BinaryCats\Exportify\Contracts\ExportFactory::class);
});

it('will_create_exportable', function (): void {
    $exportable = $this->factory->exportable();

    expect($exportable)
        ->toBeInstanceOf(Exportable::class)
        ->toBeInstanceOf(FooExportable::class);
});

it('will_return_defaults', function (): void {
    expect($this->factory->defaults())
        ->toBe(['foo' => 'bar']);
});

it('will_return_livewire_class', function (): void {
    expect($this->factory->livewire())
        ->toBe(ExportableLivewireFixture::class);
});
