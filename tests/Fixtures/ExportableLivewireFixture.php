<?php

namespace BinaryCats\Exportify\Tests\Fixtures;

use Livewire\Component;

class ExportableLivewireFixture extends Component
{
    /**
     * Render an exportable livewire component.
     */
    public function render(): string
    {
        return <<<'blade'
<div>foo-bar-component</div>
blade;
    }
}
