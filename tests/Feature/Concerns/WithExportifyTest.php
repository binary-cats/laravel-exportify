<?php

use BinaryCats\Exportify\Concerns\WithExportify;
use BinaryCats\Exportify\Contracts\Exportable;
use BinaryCats\Exportify\Contracts\HandlesExport;
use BinaryCats\Exportify\Tests\Fixtures\FakeExportHandler;
use BinaryCats\Exportify\Tests\Fixtures\FooExportable;
use Illuminate\Contracts\Auth\Guard;
use Illuminate\Foundation\Auth\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Bus;
use Illuminate\Support\Facades\Storage;
use Livewire\Component;
use Livewire\Livewire;

use function Pest\Laravel\mock;

class DummyComponent extends Component
{
    use WithExportify;

    public function render()
    {
        return <<<'blade'
<div>Fancy Pants</div>
blade;
    }

    public function validateExportableAttributes(): array
    {
        return $this->exportable->defaults();
    }

    public function handleFinally(): void {}
}

beforeEach(function () {
    $this->exportable = mock(Exportable::class);
    $this->handler = mock(HandlesExport::class);

    // Create a mock user
    $this->user = mock(User::class);
    $this->user->shouldReceive('getAuthIdentifier')->andReturn(1);

    // Create a guard mock for all tests
    $this->guard = mock(Guard::class);
    $this->guard->shouldReceive('id')->andReturn(1);
    $this->guard->shouldReceive('user')->andReturn($this->user);

    // Mock Auth facade to return our guard
    Auth::shouldReceive('guard')->andReturn($this->guard);
});

it('will_property_mount_the_exportable', function (): void {
    $exportable = FooExportable::make();

    $component = Livewire::test(DummyComponent::class, [
        'exportable' => $exportable,
    ]);

    $component
        ->assertSet('exportable', $exportable)
        ->assertSet('exportableArguments', ['bar' => 'baz']);
});

it('will_handle_success_correctly', function (): void {
    $exportable = FooExportable::make();

    $event = [
        'disk' => 'local',
        'filePath' => 'exports/test.csv',
    ];

    // Mock Storage facade consistently
    Storage::fake('local');
    Storage::disk('local')->put('exports/test.csv', 'test content');

    // Mock Storage download
    Storage::shouldReceive('disk')
        ->with('local')
        ->andReturnSelf();

    Storage::shouldReceive('download')
        ->with('exports/test.csv')
        ->andReturnSelf();

    $component = Livewire::test(DummyComponent::class, ['exportable' => $exportable]);
    $component->call('handleSuccess', $event);

    $component->assertDispatched('exportify:success', $event);
});

it('will_handle_failure_correctly', function (): void {
    $exportable = FooExportable::make();

    $event = [
        'disk' => 'local',
        'filePath' => 'exports/test.csv',
        'error' => 'Export failed',
    ];

    $component = Livewire::test(DummyComponent::class, ['exportable' => $exportable]);
    $component->call('handleFail', $event);

    $component->assertDispatched('exportify:failed', $event);
});

it('will_export_data_correctly', function (): void {
    Bus::fake();

    mock(FakeExportHandler::class, function ($mock) {
        $mock->expects('arguments')
            ->twice()
            ->with(['bar' => 'baz'])
            ->andReturnSelf();

        $mock->expects('fileName')
            ->andReturn('test.csv');

        $mock->expects('queue')
            ->andReturnSelf();

        $mock->expects('chain')
            ->andReturnSelf();
    });

    $exportable = FooExportable::make();

    $component = Livewire::test(DummyComponent::class, [
        'exportable' => $exportable,
    ]);

    $component->call('export');

});
