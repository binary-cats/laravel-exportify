<?php

namespace BinaryCats\Exportify\Components;

use BinaryCats\Exportify\Contracts\Exportable as ExportableContract;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class Exportable extends Component
{
    public function __construct(
        public readonly ExportableContract $exportable
    ) {}

    /**
     * Get the contents that represent the exportable component.
     */
    public function render(): View
    {
        return view('exportify::components.exportable');
    }
}
