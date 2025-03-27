<?php

use BinaryCats\Exportify\Concerns\ExportableCollection;
use BinaryCats\Exportify\Contracts\ExportFactory;
use BinaryCats\Exportify\Contracts\Exportable;
use BinaryCats\Exportify\Exportify;
use BinaryCats\Exportify\Tests\Fixtures\FooExportFactory;
use BinaryCats\Exportify\Tests\Fixtures\BarExportFactory;
use BinaryCats\Exportify\Tests\Fixtures\BazExportFactory;
use BinaryCats\Exportify\Exceptions\ExportifyException;
use Illuminate\Support\Facades\Gate;

test('it will register and find exports', function () {
    $exportify = new Exportify();
    $factory = new FooExportFactory();
    
    $exportify->register('foo', $factory);

    expect($exportify->find('foo'))
        ->toBeInstanceOf(ExportFactory::class)
        ->and($exportify->find('foo')->exportable())
        ->toBeInstanceOf(Exportable::class);

    expect(fn() => $exportify->find('non-existent'))
        ->toThrow(ExportifyException::class, 'Export factory [non-existent] is not registered.');
});

test('it will filter exports by tag', function () {
    $exportify = new Exportify();
    $exportify->register('foo', new FooExportFactory());
    $exportify->register('bar', new BarExportFactory());
    $exportify->register('baz', new BazExportFactory());

    expect($exportify->tagged('common'))
        ->toBeInstanceOf(ExportableCollection::class)
        ->toHaveCount(2);

    expect($exportify->tagged('tag1'))->toHaveCount(1);
    expect($exportify->tagged('tag2'))->toHaveCount(1);
    expect($exportify->tagged('tag3'))->toHaveCount(1);
    expect($exportify->tagged(['tag1', 'common']))->toHaveCount(2);
});

test('it will filter available exports', function () {
    $exportify = new Exportify();
    $exportify->register('foo', new FooExportFactory());

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
    $exportify = new Exportify();
    expect($exportify->all())->toBeInstanceOf(ExportableCollection::class);
});

test('it will unregister export', function () {
    $exportify = new Exportify();
    $exportify->register('foo', new FooExportFactory());
    expect($exportify->all())->toHaveCount(1);

    $exportify->unregister('foo');
    expect($exportify->all())->toHaveCount(0);
});

test('it will flush all exports', function () {
    $exportify = new Exportify();
    $exportify->register('foo', new FooExportFactory());
    $exportify->register('bar', new BarExportFactory());
    expect($exportify->all())->toHaveCount(2);

    $exportify->flush();
    expect($exportify->all())->toHaveCount(0);
}); 