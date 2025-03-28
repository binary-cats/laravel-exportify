<?php

use BinaryCats\Exportify\Concerns\WithExportify;
use BinaryCats\Exportify\Contracts\Exportable;
use BinaryCats\Exportify\Contracts\HandlesExport;
use BinaryCats\Exportify\Events\ExportFailed;
use BinaryCats\Exportify\Events\ExportSuccessful;
use BinaryCats\Exportify\Jobs\DispatchExportCompletedNotification;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Livewire\Component;
use Livewire\Livewire;
use function Pest\Laravel\mock;

class DummyComponent extends Component
{
    use WithExportify;

    public function render()
    {
        return view('dummy-view');
    }
}

beforeEach(function () {
    $this->exportable = mock(Exportable::class);
    $this->handler = mock(HandlesExport::class);
    $this->guard = Auth::partialMock();
    $this->user = mock(Authenticatable::class)->makePartial();
});

it('will_mount_exportify_trait_correctly', function (): void {
    $this->exportable->shouldReceive('defaults')->once()->andReturn(['default' => 'value']);

    $component = new DummyComponent();
    $component->mountWithExportify($this->exportable);

    expect($component->exportable)->toBe($this->exportable)
        ->and($component->exportableArguments)->toBe(['default' => 'value']);
});

it('will_boot_exportify_trait_correctly', function (): void {
    $this->guard->shouldReceive('id')->once()->andReturn(1);

    $component = new DummyComponent();
    $component->bootWithExportify($this->guard);

    expect($component->listeners)->toHaveKey('echo:private-user.1,\\'.ExportSuccessful::class)
        ->and($component->listeners)->toHaveKey('echo:private-user.1,\\'.ExportFailed::class);
});

it('will_handle_success_correctly', function (): void {
    Storage::fake('local');
    
    $event = [
        'disk' => 'local',
        'filePath' => 'exports/test.csv'
    ];

    Storage::disk('local')->put('exports/test.csv', 'test content');

    $component = Livewire::test(DummyComponent::class);
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
