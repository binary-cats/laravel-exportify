<?php

namespace BinaryCats\Exportify\Contracts;

interface ExportFactory
{
    /**
     * Default values for the export call within a generator.
     *
     * @return array<string, mixed>
     */
    public function defaults(): array;

    /**
     * Make the exportable class using defaults and specified attributes.
     *
     * @param  array<string, mixed>  $attributes
     * @return Exportable
     */
    public function exportable(array $attributes = []): Exportable;

    /**
     * Name of the Livewire class responsible for the report.
     *
     * @return string|class-string
     */
    public function livewire(): string;
} 