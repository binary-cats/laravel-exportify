<?php

namespace BinaryCats\Exportify\Tests\Feature\Commands;

beforeEach(function () {
    $this->app->make('files')->deleteDirectory(app_path('Exportables'), true);
    $this->app->make('files')->deleteDirectory(base_path('tests'), true);
});

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
