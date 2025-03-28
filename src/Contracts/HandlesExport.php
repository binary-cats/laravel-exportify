<?php

namespace BinaryCats\Exportify\Contracts;

interface HandlesExport
{
    /**
     * Get or set the arguments for the export.
     */
    public function arguments(array|string|null $value = null, $default = null): mixed;
}
