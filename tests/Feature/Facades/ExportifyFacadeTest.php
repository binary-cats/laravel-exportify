<?php

use BinaryCats\Exportify\Concerns\ExportableCollection;
use BinaryCats\Exportify\Contracts\Exportable;
use BinaryCats\Exportify\Contracts\ExportFactory;
use BinaryCats\Exportify\Exceptions\ExportifyException;
use BinaryCats\Exportify\Facades\Exportify;
use BinaryCats\Exportify\Tests\Fixtures\FooExportable;
use Illuminate\Support\Facades\Gate;

it('will_get_handle_exportify_registration', function (): void {
    $factory = FooExportable::make();
    Exportify::register('foo', $factory);

    expect(Exportify::all())
        ->toBeInstanceOf(ExportableCollection::class)
        ->toHaveCount(1);
});

it('will_get_available_exports', function (): void {
    $factory = FooExportable::make();
    
    Exportify::register('foo', $factory);

    Gate::shouldReceive('getPolicyFor')
        ->once()
        ->with('foo')
        ->andReturn(true);

    Gate::shouldReceive('allows')
        ->once()
        ->with('view', 'foo')
        ->andReturn(true);

    expect(Exportify::available())
        ->toBeInstanceOf(ExportableCollection::class)
        ->toHaveCount(1);
});

it('will_get_exports_by_tag', function (): void {
    $factory = FooExportable::make();
    Exportify::register('foo', $factory);

    expect(Exportify::tagged('foo'))
        ->toBeInstanceOf(ExportableCollection::class)
        ->toHaveCount(1);

    expect(Exportify::tagged('non-existent-tag'))
        ->toBeInstanceOf(ExportableCollection::class)
        ->toHaveCount(0);
});

it('will_find_export_by_name', function (): void {
    $factory = FooExportable::make();
    Exportify::register('foo', $factory);

    expect(Exportify::find('foo'))
        ->toBeInstanceOf(Exportable::class);
});

it('will_throw_exception_when_export_not_found', function (): void {
    expect(fn () => Exportify::find('non-existent'))
        ->toThrow(ExportifyException::class, 'Export factory [non-existent] is not registered.');
});

it('will_register_and_unregister_export', function (): void {
    $factory = FooExportable::make();

    // Register
    Exportify::register('foo', $factory);
    expect(Exportify::all())->toHaveCount(1);

    // Unregister
    Exportify::unregister('foo');
    expect(Exportify::all())->toHaveCount(0);
});

beforeEach(function () {
    Exportify::flush();
});
