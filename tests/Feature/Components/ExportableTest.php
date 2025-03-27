<?php

namespace BinaryCats\Exportify\Tests\Feature\Components;

use BinaryCats\Exportify\Tests\Fixtures\FooExportFactory;
use BinaryCats\Exportify\Tests\Fixtures\ExportableLivewireFixture;

it('will_render_exportable_component', function(): void {
    
    $factory = new FooExportFactory();

    $blade = $this->blade('<x-exportify-exportable :export-factory="$factory" />', ['factory' => $factory]);
    
    $blade->assertSeeLivewire(ExportableLivewireFixture::class);
}); 