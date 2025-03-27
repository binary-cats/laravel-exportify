<?php

namespace BinaryCats\Exportify\Tests\Fixtures;

use BinaryCats\Exportify\Contracts\Exportable;

class BazExportable implements Exportable
{
    public function download(array $parameters = []): mixed
    {
        return 'baz:test';
    }

    public function queue(array $parameters = []): mixed
    {
        return 'baz:test';
    }

    public function raw(array $parameters = []): mixed
    {
        return 'baz:test';
    }

    public function store(array $parameters = []): mixed
    {
        return 'baz:test';
    }

    public function tags(): array
    {
        return ['tag3', 'special'];
    }
} 