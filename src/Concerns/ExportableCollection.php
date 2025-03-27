<?php

namespace BinaryCats\Exportify\Concerns;

use BinaryCats\Exportify\Contracts\ExportFactory;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;

class ExportableCollection extends Collection
{
    /**
     * Filter export factories by tags.
     */
    public function tagged(string|array $tags): static
    {
        $tags = Arr::wrap($tags);

        return $this->filter(function (ExportFactory $factory) use ($tags) {

            $exportable = $factory->exportable();

            return collect($exportable->tags())
                ->intersect($tags)
                ->isNotEmpty();
        });
    }
}
