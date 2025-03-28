<?php

declare(strict_types=1);

namespace BinaryCats\Exportify\Attributes;

use Attribute;
use Livewire\Component;

#[Attribute(Attribute::TARGET_CLASS)]
class ExportableDisk
{
    public function __construct(
        private readonly string $disk
    ) {}

    /**
     * Set the disk to use for the export.
     */
    public function handle(Component $component): void
    {
        $component->exportable_disk = $this->disk;
    }
}
