<?php

namespace BinaryCats\Exportify\Facades;

use BinaryCats\Exportify\Concerns\ExportableCollection;
use BinaryCats\Exportify\Contracts\Exportable;
use Illuminate\Support\Facades\Facade;

/**
 * @method static ExportableCollection<string, Exportable> all()
 * @method static ExportableCollection<string, Exportable> available()
 * @method static ExportableCollection<string, Exportable> tagged(string|array $tag)
 * @method static Exportable find(string $name)
 * @method static void register(string $name, Exportable $export)
 * @method static void unregister(string $name)
 * @method static static flush()
 *
 * @see \BinaryCats\Exportify\Exportify
 */
class Exportify extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'exportify';
    }
}
