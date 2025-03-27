<?php

namespace BinaryCats\Exportify\Components;

use BinaryCats\Exportify\Facades\Exportify;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Collection;
use Illuminate\View\Component;

class ListExportables extends Component
{
    /**
     * Create a new component instance.
     */
    public function __construct(
        public readonly string|array $tagged = []
    ) {
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View
    {
        return view('exportify::components.list-exportables', [
            'exports' => $this->exporters(),
        ]);
    }

    /**
     * Get the exporters that should be displayed.
     */
    protected function exporters(): Collection
    {
        return Exportify::available()
            ->when($this->tagged, fn(Collection $all) => $all->tagged($this->tagged));
    }
} 