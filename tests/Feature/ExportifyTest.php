<?php

use BinaryCats\Exportify\Concerns\ExportableCollection;
use BinaryCats\Exportify\Contracts\Exportable;
use BinaryCats\Exportify\Contracts\ExportFactory;
use BinaryCats\Exportify\Exceptions\ExportifyException;
use BinaryCats\Exportify\Exportify;
use BinaryCats\Exportify\Tests\Fixtures\BarExportable;
use BinaryCats\Exportify\Tests\Fixtures\BazExportable;
use BinaryCats\Exportify\Tests\Fixtures\FooExportable;
use Illuminate\Support\Facades\Gate;

test('it will register and find exports', function () {
    $exportify = new Exportify;
    $factory = FooExportable::make();

    $exportify->register('foo', $factory);

    expect($exportify->find('foo'))
        ->toBeInstanceOf(ExportFactory::class)
        ->and($exportify->find('foo')->exportable())
        ->toBeInstanceOf(Exportable::class);

    expect(fn () => $exportify->find('non-existent'))
        ->toThrow(ExportifyException::class, 'Export factory [non-existent] is not registered.');
});

test('it will filter exports by tag', function () {
    $exportify = new Exportify;
    $exportify->register('foo', FooExportable::make());
    $exportify->register('bar', BarExportable::make());
    $exportify->register('baz', BazExportable::make());

    expect($exportify->tagged('common'))
        ->toBeInstanceOf(ExportableCollection::class)
        ->toHaveCount(2);

    expect($exportify->tagged('foo'))->toHaveCount(1);
    expect($exportify->tagged('bar'))->toHaveCount(1);
    expect($exportify->tagged('baz'))->toHaveCount(1);
    expect($exportify->tagged(['foo', 'common']))->toHaveCount(2);
});

test('it will filter available exports', function () {
    $exportify = new Exportify;
    $exportify->register('foo', FooExportable::make());

    // Test with Gate allowing access
    Gate::partialMock();
    Gate::shouldReceive('allows')
        ->once()
        ->with('view', 'foo')
        ->andReturn(true);

    expect($exportify->available())
        ->toBeInstanceOf(ExportableCollection::class)
        ->toHaveCount(1);

    // Test with Gate denying access
    Gate::partialMock();
    Gate::shouldReceive('allows')
        ->once()
        ->with('view', 'foo')
        ->andReturn(false);

    expect($exportify->available())->toHaveCount(0);
});

test('it will return exportable collection for all', function () {
    $exportify = new Exportify;
    expect($exportify->all())->toBeInstanceOf(ExportableCollection::class);
});

test('it will unregister export', function () {
    $exportify = new Exportify;
    $exportify->register('foo', FooExportable::make());
    expect($exportify->all())->toHaveCount(1);

    $exportify->unregister('foo');
    expect($exportify->all())->toHaveCount(0);
});

test('it will flush all exports', function () {
    $exportify = new Exportify;
    $exportify->register('foo', FooExportable::make());
    $exportify->register('bar', BarExportable::make());
    expect($exportify->all())->toHaveCount(2);

    $exportify->flush();
    expect($exportify->all())->toHaveCount(0);
});
