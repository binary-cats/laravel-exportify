<?php

namespace BinaryCats\Exportify\Components;

use BinaryCats\Exportify\Contracts\ExportFactory;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class Exportable extends Component
{
    public function __construct(
        public readonly ExportFactory $exportFactory
    ) {}

    /**
     * Get the contents that represent the exportable component.
     */
    public function render(): View
    {
        return view('exportify::components.exportable');
    }
}
