<?php

namespace BinaryCats\Exportify\Tests\Fixtures;

use BinaryCats\Exportify\Contracts\Exportable;

class BarExportable implements Exportable
{
    public function download(array $parameters = []): mixed
    {
        return 'bar:test';
    }

    public function queue(array $parameters = []): mixed
    {
        return 'bar:test';
    }

    public function raw(array $parameters = []): mixed
    {
        return 'bar:test';
    }

    public function store(array $parameters = []): mixed
    {
        return 'bar:test';
    }

    public function tags(): array
    {
        return ['tag2', 'common'];
    }
} 