<?php

use BinaryCats\Exportify\Contracts\HandlesExport;
use BinaryCats\Exportify\Exportable;
use BinaryCats\Exportify\ExportableConfig;

class TestExportable extends Exportable
{
    public static function config(): ExportableConfig
    {
        return new ExportableConfig(
            defaults: ['test' => 'value'],
            handler: TestHandler::class,
            livewire: 'test-component'
        );
    }

    public function tags(): array
    {
        return ['test-tag'];
    }
}

class TestHandler implements HandlesExport
{
    private array $args = [];

    public function handle(array $arguments = []): mixed
    {
        return $arguments;
    }

    public function arguments(array|string|null $value = null, $default = null): mixed
    {
        if ($value === null) {
            return $this->args;
        }

        if (is_string($value)) {
            return $this->args[$value] ?? $default;
        }

        $this->args = array_merge($this->args, $value);

        return $this;
    }
}

it('will_make_exportable_instance', function (): void {
    $exportable = TestExportable::make();

    expect($exportable)->toBeInstanceOf(TestExportable::class);
});

it('will_return_default_arguments', function (): void {
    $exportable = TestExportable::make();

    expect($exportable->defaults())->toBe(['test' => 'value']);
});

it('will_return_handler_instance', function (): void {
    $exportable = TestExportable::make();

    expect($exportable->handler())->toBeInstanceOf(TestHandler::class);
});

it('will_return_livewire_component_name', function (): void {
    $exportable = TestExportable::make();

    expect($exportable->livewire())->toBe('test-component');
});

it('will_convert_to_livewire_array', function (): void {
    $exportable = TestExportable::make();

    $livewireData = $exportable->toLivewire();

    expect($livewireData)
        ->toBeArray()
        ->toHaveKeys(['defaults', 'handler', 'livewire']);
});

it('will_create_from_livewire_data', function (): void {
    $originalExportable = TestExportable::make();
    $livewireData = $originalExportable->toLivewire();

    $newExportable = TestExportable::fromLivewire($livewireData);

    expect($newExportable)
        ->toBeInstanceOf(TestExportable::class)
        ->and($newExportable->defaults())->toBe($originalExportable->defaults())
        ->and($newExportable->livewire())->toBe($originalExportable->livewire());
});

it('will_return_tags', function (): void {
    $exportable = TestExportable::make();

    expect($exportable->tags())->toBe(['test-tag']);
});

it('will_handle_arguments_correctly', function (): void {
    $handler = new TestHandler;

    // Test setting arguments
    $handler->arguments(['test' => 'value']);
    expect($handler->arguments())->toBe(['test' => 'value']);

    // Test getting specific argument
    expect($handler->arguments('test'))->toBe('value');

    // Test getting non-existent argument with default
    expect($handler->arguments('missing', 'default'))->toBe('default');
});
