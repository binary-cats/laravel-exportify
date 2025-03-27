<?php

use BinaryCats\Exportify\Concerns\ExportableCollection;
use BinaryCats\Exportify\Contracts\ExportFactory;
use BinaryCats\Exportify\Exceptions\ExportifyException;
use BinaryCats\Exportify\Facades\Exportify;
use BinaryCats\Exportify\Tests\Fixtures\FooExportFactory;
use Illuminate\Support\Facades\Gate;

it('will_get_all_exports', function(): void {
    $factory = new FooExportFactory();
    Exportify::register('foo', $factory);

    expect(Exportify::all())
        ->toBeInstanceOf(ExportableCollection::class)
        ->toHaveCount(1);
});

it('will_get_available_exports', function(): void {
    $factory = new FooExportFactory();
    Exportify::register('foo', $factory);

    Gate::shouldReceive('allows')
        ->once()
        ->with('view', 'foo')
        ->andReturn(true);

    expect(Exportify::available())
        ->toBeInstanceOf(ExportableCollection::class)
        ->toHaveCount(1);
});

it('will_get_exports_by_tag', function(): void {
    $factory = new FooExportFactory();
    Exportify::register('foo', $factory);

    expect(Exportify::tagged('tag1'))
        ->toBeInstanceOf(ExportableCollection::class)
        ->toHaveCount(1);

    expect(Exportify::tagged('non-existent-tag'))
        ->toBeInstanceOf(ExportableCollection::class)
        ->toHaveCount(0);
});

it('will_find_export_by_name', function(): void {
    $factory = new FooExportFactory();
    Exportify::register('foo', $factory);

    expect(Exportify::find('foo'))
        ->toBeInstanceOf(ExportFactory::class);
});

it('will_throw_exception_when_export_not_found', function(): void {
    expect(fn() => Exportify::find('non-existent'))
        ->toThrow(ExportifyException::class, 'Export factory [non-existent] is not registered.');
});

it('will_register_and_unregister_export', function(): void {
    $factory = new FooExportFactory();
    
    // Register
    Exportify::register('foo', $factory);
    expect(Exportify::all())->toHaveCount(1);

    // Unregister
    Exportify::unregister('foo');
    expect(Exportify::all())->toHaveCount(0);
});

beforeEach(function() {
    Exportify::flush();
}); 