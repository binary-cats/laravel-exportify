<?php

use BinaryCats\Exportify\Concerns\ExportFactoryConfig;
use BinaryCats\Exportify\Tests\Fixtures\ExportableLivewireFixture;
use BinaryCats\Exportify\Tests\Fixtures\FooExportable;
use InvalidArgumentException;

it('will_create_config_with_valid_data', function (): void {
    $config = new ExportFactoryConfig([
        'exportable' => FooExportable::class,
        'livewire' => ExportableLivewireFixture::class,
        'defaults' => ['foo' => 'bar'],
    ]);

    expect($config)
        ->exportable->toBe(FooExportable::class)
        ->livewire->toBe(ExportableLivewireFixture::class)
        ->defaults->toBe(['foo' => 'bar']);
});

it('will_create_config_with_minimal_data', function (): void {
    $config = new ExportFactoryConfig([
        'exportable' => FooExportable::class,
        'livewire' => ExportableLivewireFixture::class,
    ]);

    expect($config)
        ->exportable->toBe(FooExportable::class)
        ->livewire->toBe(ExportableLivewireFixture::class)
        ->defaults->toBe([]);
});

it('will_throw_exception_when_exportable_is_missing', function (): void {
    expect(fn () => new ExportFactoryConfig([
        'livewire' => ExportableLivewireFixture::class,
    ]))->toThrow(InvalidArgumentException::class, 'The exportable class must be provided');
});

it('will_throw_exception_when_livewire_is_missing', function (): void {
    expect(fn () => new ExportFactoryConfig([
        'exportable' => FooExportable::class,
    ]))->toThrow(InvalidArgumentException::class, 'The Livewire component class must be provided');
});

it('will_throw_exception_when_exportable_class_does_not_exist', function (): void {
    expect(fn () => new ExportFactoryConfig([
        'exportable' => 'NonExistentClass',
        'livewire' => ExportableLivewireFixture::class,
    ]))->toThrow(InvalidArgumentException::class, 'The exportable class does not exist');
});

it('will_throw_exception_when_livewire_class_does_not_exist', function (): void {
    expect(fn () => new ExportFactoryConfig([
        'exportable' => FooExportable::class,
        'livewire' => 'NonExistentClass',
    ]))->toThrow(InvalidArgumentException::class, 'The Livewire component class does not exist');
});

it('will_throw_exception_when_exportable_does_not_implement_interface', function (): void {
    expect(fn () => new ExportFactoryConfig([
        'exportable' => ExportableLivewireFixture::class, // Using Livewire class as it exists but doesn't implement Exportable
        'livewire' => ExportableLivewireFixture::class,
    ]))->toThrow(InvalidArgumentException::class, 'The exportable class must implement the Exportable interface');
});

it('will_throw_exception_when_defaults_is_not_array', function (): void {
    expect(fn () => new ExportFactoryConfig([
        'exportable' => FooExportable::class,
        'livewire' => ExportableLivewireFixture::class,
        'defaults' => 'not an array',
    ]))->toThrow(InvalidArgumentException::class, 'The defaults must be an array');
});
