<?php

namespace Tests\Feature\Commands;

use BinaryCats\Exportify\Commands\MakeExportCommand;
use BinaryCats\Exportify\Tests\TestCase;
use Illuminate\Support\Facades\File;

class MakeExportCommandTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        // Create necessary directories if they don't exist
        if (!File::exists($this->app->path('Exportables'))) {
            File::makeDirectory($this->app->path('Exportables'), 0755, true);
        }
        if (!File::exists($this->app->path('Policies'))) {
            File::makeDirectory($this->app->path('Policies'), 0755, true);
        }

        // Clean up any previously generated files
        $this->cleanupFiles();
    }

    protected function tearDown(): void
    {
        // Clean up generated files
        $this->cleanupFiles();

        parent::tearDown();
    }

    protected function cleanupFiles(): void
    {
        $files = [
            $this->app->path('Exportables/TestExportable.php'),
            $this->app->path('Policies/TestExportablePolicy.php'),
        ];

        foreach ($files as $file) {
            if (File::exists($file)) {
                File::delete($file);
            }
        }
    }

    public function test_it_will_create_exportable_class(): void
    {
        // Act
        $this->artisan('make:exportify', ['name' => 'TestExportable'])
            ->assertSuccessful();

        // Assert
        $filePath = $this->app->path('Exportables/TestExportable.php');
        $this->assertTrue(File::exists($filePath));
        
        $content = File::get($filePath);
        expect($content)
            ->toContain('namespace App\Exportables')
            ->toContain('class TestExportable extends Exportable')
            ->toContain('public static function config(): ExportableConfig');
    }

    public function test_it_will_create_exportable_with_policy(): void
    {
        // Act
        $this->artisan('make:exportify', [
            'name' => 'TestExportable',
            '--policy' => true,
        ])->assertSuccessful();

        // Assert
        $exportablePath = $this->app->path('Exportables/TestExportable.php');
        $policyPath = $this->app->path('Policies/TestExportablePolicy.php');

        $this->assertTrue(File::exists($exportablePath));
        $this->assertTrue(File::exists($policyPath));

        $policyContent = File::get($policyPath);
        expect($policyContent)
            ->toContain('namespace App\Policies')
            ->toContain('class TestExportablePolicy');
    }

    public function test_it_will_not_overwrite_existing_exportable(): void
    {
        // First creation
        $this->artisan('make:exportify', ['name' => 'TestExportable'])
            ->assertSuccessful();

        // Get the original content
        $originalContent = File::get($this->app->path('Exportables/TestExportable.php'));

        // Second creation attempt - when user chooses not to overwrite,
        // Laravel's GeneratorCommand returns false (non-zero exit code)
        $this->artisan('make:exportify', ['name' => 'TestExportable'])
            ->expectsConfirmation(
                'A App\Exportables\TestExportable exportable already exists. Do you want to replace it?',
                'no'
            )
            ->assertExitCode(1);

        // Verify the content hasn't changed
        $this->assertEquals(
            $originalContent,
            File::get($this->app->path('Exportables/TestExportable.php'))
        );
    }
} 