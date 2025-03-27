<?php

namespace BinaryCats\Exportify;

use BinaryCats\Exportify\Commands\MakeExportCommand;
use BinaryCats\Exportify\Components\Exportable;
use BinaryCats\Exportify\Components\ListExportables;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;

class ExportifyServiceProvider extends PackageServiceProvider
{
    /**
     * Configure the package.
     */
    public function configurePackage(Package $package): void
    {
        /*
         * This class is a Package Service Provider
         *
         * @see https://github.com/spatie/laravel-package-tools
         */
        $package
            ->name('laravel-exportify')
            ->hasConfigFile()
            ->hasViews()
            ->hasCommand(MakeExportCommand::class)
            ->hasMigration('create_export_history_table')
            ->hasViewComponent('exportify', Exportable::class)
            ->hasViewComponent('exportify', ListExportables::class);
    }

    /**
     * Register any application services.
     */
    public function packageRegistered(): void
    {
        $this->app->singleton('exportify', function ($app) {
            return new Exportify();
        });
    }
}
