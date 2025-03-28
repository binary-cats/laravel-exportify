<?php

namespace BinaryCats\Exportify\Components;

use BinaryCats\Exportify\Contracts\Exportable as ExportableContract;
use BinaryCats\Exportify\Facades\Exportify;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class Exportable extends Component
{
    public function __construct(
        protected readonly ExportableContract|string $exportable
    ) {}

    /**
     * Get the contents that represent the exportable component.
     */
    public function render(): View
    {
        return view('exportify::components.exportable')
            ->with('exportable', $this->exportable());
    }

    private function exportable(): ExportableContract
    {
        if (is_string($this->exportable)) {
            return Exportify::find($this->exportable);
        }

        return $this->exportable;
    }
}
