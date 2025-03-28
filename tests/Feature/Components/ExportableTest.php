<?php

namespace BinaryCats\Exportify\Tests\Feature\Components;

use BinaryCats\Exportify\Tests\Fixtures\ExportableLivewireFixture;
use BinaryCats\Exportify\Tests\Fixtures\FooExportable;

it('will_render_exportable_component', function (): void {

    $exportable = FooExportable::make();

    $blade = $this->blade('<x-exportify-exportable :exportable="$exportable" />', ['exportable' => $exportable]);

    $blade->assertSeeLivewire(ExportableLivewireFixture::class);
});
