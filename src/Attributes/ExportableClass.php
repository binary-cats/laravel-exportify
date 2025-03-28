<?php

declare(strict_types=1);

namespace BinaryCats\Exportify\Attributes;

use Attribute;
use BinaryCats\Exportify\Contracts\Exportable;
use BinaryCats\Exportify\Exportify;
use Livewire\Component;

#[Attribute(Attribute::TARGET_CLASS)]
class ExportableClass
{
    /**
     * Create a new attribute instance.
     */
    public function __construct(
        private readonly string|Exportable $exportable
    ) {}

    /**
     * Handle the attribute.
     */
    public function handle(Component $component): void
    {
        if (is_string($this->exportable)) {
            $component->exportable = app(Exportify::class)->find($this->exportable);

            return;
        }

        $component->exportable = $this->exportable;
    }
}
