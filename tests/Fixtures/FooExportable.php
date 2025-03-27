<?php

namespace BinaryCats\Exportify\Tests\Fixtures;

use BinaryCats\Exportify\Contracts\Exportable;

class FooExportable implements Exportable
{
    public function download(array $parameters = []): mixed
    {
        return 'foo:test';
    }

    public function queue(array $parameters = []): mixed
    {
        return 'foo:test';
    }

    public function raw(array $parameters = []): mixed
    {
        return 'foo:test';
    }

    public function store(array $parameters = []): mixed
    {
        return 'foo:test';
    }

    public function tags(): array
    {
        return ['tag1', 'common'];
    }
}
