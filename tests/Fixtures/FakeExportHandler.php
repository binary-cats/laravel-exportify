<?php

namespace BinaryCats\Exportify\Tests\Fixtures;

use BinaryCats\Exportify\Concerns\InteractsWithArguments;
use BinaryCats\Exportify\Contracts\HandlesExport;

class FakeExportHandler implements HandlesExport
{
    use InteractsWithArguments;

    public function handle(array $parameters = []): mixed
    {
        return 'fake:test';
    }
}
