<?php

use BinaryCats\Exportify\Contracts\HandlesExport;
use BinaryCats\Exportify\ExportableConfig;

it('will_create_exportable_config_instance', function (): void {
    $config = new ExportableConfig(
        handler: 'TestHandler',
        livewire: 'test-livewire',
        defaults: ['key' => 'value']
    );

    expect($config)
        ->toBeInstanceOf(ExportableConfig::class)
        ->and($config->toArray())->toBe([
            'handler' => 'TestHandler',
            'livewire' => 'test-livewire',
            'defaults' => ['key' => 'value'],
        ]);
});

it('will_convert_to_array', function (): void {
    $config = new ExportableConfig(
        handler: 'TestHandler',
        livewire: 'test-livewire',
        defaults: ['key' => 'value']
    );

    expect($config->toArray())->toBe([
        'handler' => 'TestHandler',
        'livewire' => 'test-livewire',
        'defaults' => ['key' => 'value'],
    ]);
});

it('will_create_handler_instance', function (): void {
    $mockHandler = Mockery::mock(HandlesExport::class);
    $mockHandler->shouldReceive('arguments')
        ->once()
        ->with(['key' => 'value'])
        ->andReturnSelf();

    app()->instance('TestHandler', $mockHandler);

    $config = new ExportableConfig(
        handler: 'TestHandler',
        livewire: 'test-livewire',
        defaults: ['key' => 'value']
    );

    $handler = $config->newHandler();

    expect($handler)->toBe($mockHandler);
});

it('will_create_handler_with_custom_arguments', function (): void {
    $mockHandler = Mockery::mock(HandlesExport::class);
    $mockHandler->shouldReceive('arguments')
        ->once()
        ->with(['key' => 'value'])
        ->andReturnSelf();

    $mockHandler->shouldReceive('arguments')
        ->once()
        ->with(['custom' => 'arg'])
        ->andReturnSelf();

    app()->instance('TestHandler', $mockHandler);

    $config = new ExportableConfig(
        handler: 'TestHandler',
        livewire: 'test-livewire',
        defaults: ['key' => 'value']
    );

    $handler = $config->handler(['custom' => 'arg']);

    expect($handler)->toBe($mockHandler);
});
