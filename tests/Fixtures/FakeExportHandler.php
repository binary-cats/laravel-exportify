<?php

namespace BinaryCats\Exportify\Tests\Fixtures;

use BinaryCats\Exportify\Contracts\HandlesExport;
use BinaryCats\Exportify\Concerns\InteractsWithArguments;

class FakeExportHandler implements HandlesExport
{
    use InteractsWithArguments;

    public function handle(array $parameters = []): mixed
    {
        return 'fake:test';
    }
}