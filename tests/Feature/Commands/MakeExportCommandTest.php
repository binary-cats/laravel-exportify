<?php

namespace BinaryCats\Exportify\Tests\Feature\Commands;

it('will handle rendering a new exportable', function () {
    $this->artisan('make:exportify', [
        'name' => 'TestExportable',
    ])->assertSuccessful();

    $this->assertFileExists($this->app->path('Exportables/TestExportable.php'));
});

it('will handle rendering a new exportable with a policy', function () {
    $this->artisan('make:exportify', [
        'name' => 'TestExportable',
        '--policy' => true,
    ])->assertSuccessful();
});
