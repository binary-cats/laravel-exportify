<?php

namespace BinaryCats\Exportify\Tests;

use BinaryCats\Exportify\ExportifyServiceProvider;
use BinaryCats\Exportify\Tests\Fixtures\ExportableLivewireFixture;
use Illuminate\Database\Eloquent\Factories\Factory;
use Livewire\Livewire;
use Livewire\LivewireServiceProvider;
use Orchestra\Testbench\TestCase as Orchestra;

class TestCase extends Orchestra
{
    protected function setUp(): void
    {
        parent::setUp();

        Factory::guessFactoryNamesUsing(
            fn (string $modelName) => 'BinaryCats\\Exportify\\Database\\Factories\\'.class_basename($modelName).'Factory'
        );

        Livewire::component('exportable-livewire-fixture', ExportableLivewireFixture::class);
    }

    protected function getPackageProviders($app)
    {
        return [
            LivewireServiceProvider::class,
            ExportifyServiceProvider::class,
        ];
    }

    public function getEnvironmentSetUp($app)
    {
        config()->set('database.default', 'sqlite');

        config()->set('database.connections.sqlite', [
            'driver' => 'sqlite',
            'database' => ':memory:',
            'prefix' => '',
        ]);

        foreach (\Illuminate\Support\Facades\File::allFiles(__DIR__.'/../database/migrations') as $migration) {
            (include $migration->getRealPath())->up();
        }

    }
}
