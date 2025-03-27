<?php

namespace BinaryCats\Exportify\Exceptions;

use Exception;

class ExportifyException extends Exception
{
    /**
     * Missing factory exception.
     */
    public static function missingFactory(string $name): static
    {
        return new static(__('Export factory [:name] is not registered.', ['name' => $name]));
    }
}