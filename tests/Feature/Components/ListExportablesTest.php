<?php

namespace BinaryCats\Exportify\Tests\Feature\Components;

use BinaryCats\Exportify\Facades\Exportify;
use BinaryCats\Exportify\Tests\Fixtures\FooExportFactory;
use BinaryCats\Exportify\Tests\Fixtures\BarExportFactory;
use BinaryCats\Exportify\Tests\Fixtures\ExportableLivewireFixture;
use Illuminate\Support\Facades\Gate;

it('will_render_empty_state_when_no_exports', function(): void {
    $this->blade('<x-exportify-list-exportables />')
        ->assertSee('No exports available.');
});

it('will_render_list_of_exportables', function(): void {
    
    Exportify::register('foo', new FooExportFactory());
    Exportify::register('bar', new BarExportFactory());

    // Mock Gate to allow access
    Gate::shouldReceive('allows')
        ->twice()
        ->with('view', \Mockery::anyOf('foo', 'bar'))
        ->andReturn(true);

    $view = $this->blade('<x-exportify-list-exportables/>');
    
    // Assert the Livewire component is present
    $view->assertSeeLivewire(ExportableLivewireFixture::class);
});
