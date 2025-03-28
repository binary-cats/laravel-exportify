<?php

use BinaryCats\Exportify\Concerns\WithExportify;
use BinaryCats\Exportify\Contracts\Exportable;
use BinaryCats\Exportify\Contracts\HandlesExport;
use BinaryCats\Exportify\Events\ExportFailed;
use BinaryCats\Exportify\Events\ExportSuccessful;
use BinaryCats\Exportify\Jobs\DispatchExportCompletedNotification;
use BinaryCats\Exportify\Tests\Fixtures\FooExportable;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Foundation\Auth\User;
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
}

beforeEach(function () {
    $this->exportable = mock(Exportable::class);
    $this->handler = mock(HandlesExport::class);
});

it('will_property_mount_the_exportable', function (): void {
    
    $exportable = FooExportable::make();

    $user = mock(User::class, function ($mock) {
        $mock->shouldReceive('getAuthIdentifier')
        ->times(2)
        ->andReturn(1);
    })->makePartial();
    
    $component = Livewire::actingAs($user)
        ->test(DummyComponent::class, ['exportable' => $exportable]);

    $component
        ->assertSet('exportable', $exportable)
        ->assertSet('exportableArguments', ['bar' => 'baz']);
        
    expect($component->invade()->listeners)
        ->toHaveKey('echo:private-user.1,\\'.ExportSuccessful::class)
        ->toHaveKey('echo:private-user.1,\\'.ExportFailed::class);
});

it('will_handle_success_correctly', function (): void {
    $exportable = FooExportable::make();

    Storage::fake('local');
    
    $event = [
        'disk' => 'local',
        'filePath' => 'exports/test.csv'
    ];

    Storage::shouldReceive('download')
        ->with('exports/test.csv')
        ->andReturnSelf();

    $component = Livewire::test(DummyComponent::class, ['exportable' => $exportable]);
    $component->call('handleSuccess', $event);

    $component->assertDispatched('exportify:success', $event);
});

it('will_handle_failure_correctly', function (): void {
    $event = [
        'disk' => 'local',
        'filePath' => 'exports/test.csv',
        'error' => 'Export failed'
    ];

    $this->guard
        ->shouldReceive('user')
        ->once()
        ->andReturn();

    $component = Livewire::test(DummyComponent::class);
    $component->call('handleFail', $event);

    $component->assertDispatched('exportify:failed', $event);
});

it('will_export_data_correctly', function (): void {
    $user = new stdClass();
    $this->guard->shouldReceive('user')->once()->andReturn($user);

    $this->exportable->shouldReceive('handler')->once()->andReturn($this->handler);
    
    $this->handler->shouldReceive('fileName')->once()->andReturn('test.csv');
    $this->handler->shouldReceive('queue')->once()->with(
        filePath: 'test.csv',
        disk: null
    )->andReturnSelf();
    $this->handler->shouldReceive('chain')->once()->with(
        \Mockery::on(function ($jobs) use ($user) {
            return $jobs[0] instanceof DispatchExportCompletedNotification
                && $jobs[0]->user === $user;
        })
    );

    $component = new DummyComponent();
    $component->exportable = $this->exportable;
    $component->export($this->guard);
});
