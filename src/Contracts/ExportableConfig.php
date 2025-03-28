<?php

namespace BinaryCats\Exportify\Contracts;

interface ExportableConfig
{
    /**
     * Get the handler for this export.
     */
    public function handler(array $arguments = []): HandlesExport;
}
