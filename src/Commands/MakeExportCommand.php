<?php

namespace BinaryCats\Exportify\Commands;

use Illuminate\Console\GeneratorCommand;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use Symfony\Component\Console\Input\InputOption;

class MakeExportCommand extends GeneratorCommand
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'make:exportify';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new exportable';

    /**
     * The type of class being generated.
     *
     * @var string
     */
    protected $type = 'Export';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $name = $this->getNameInput();
        $dryRun = $this->option('dry-run');

        $files = [
            $this->getPath($this->qualifyClass($name)),
        ];

        // Create factory if requested
        if ($this->option('factory')) {
            $files[] = $this->createFactory($name, $dryRun);
        }

        // Create policy if requested
        if ($this->option('policy')) {
            $files[] = $this->createPolicy($name, $dryRun);
        }

        // Create Livewire component if requested
        if ($this->option('livewire')) {
            $files[] = $this->createLivewire($name, $dryRun);
        }

        // Create tests if requested
        if ($this->option('test')) {
            $testFiles = $this->createTests($name, $files, $dryRun);
            $files = array_merge($files, $testFiles);
        }

        // Output results
        if ($dryRun) {
            $this->info('The following files would be created:');
            foreach ($files as $file) {
                $this->line("- {$file}");
            }
        } else {
            $this->info('Created files:');
            foreach ($files as $file) {
                $this->line("- {$file}");
            }
        }

        return self::SUCCESS;
    }

    /**
     * Get the stub file for the generator.
     */
    protected function getStub(): string
    {
        return __DIR__.'/../../stubs/exportable.stub';
    }

    /**
     * Get the default namespace for the class.
     */
    protected function getDefaultNamespace($rootNamespace): string
    {
        return $rootNamespace.'\\Exports';
    }

    /**
     * Create the factory class
     */
    protected function createFactory(string $name, bool $dryRun): string
    {
        $className = Str::studly($name).'ExportFactory';
        $namespace = $this->rootNamespace().'ExportFactories';
        $path = $this->getPath($namespace.'\\'.$className);

        if (! $dryRun) {
            $this->ensureDirectoryExists(dirname($path));
            $stub = File::get(__DIR__.'/../../stubs/exportable_factory.stub');
            $exportClass = Str::studly($name).'Export';
            $content = str_replace(
                [
                    '{{ namespace }}',
                    '{{ class }}',
                    '{{ exportableNamespace }}',
                    '{{ exportableClass }}',
                    '{{ livewireClass }}',
                ],
                [
                    $namespace,
                    $className,
                    $this->rootNamespace().'Exports',
                    $exportClass,
                    'Export'.Str::studly($name),
                ],
                $stub
            );
            File::put($path, $content);
        }

        return $path;
    }

    /**
     * Create the policy class
     */
    protected function createPolicy(string $name, bool $dryRun): string
    {
        $className = Str::studly($name).'ExportPolicy';
        $namespace = $this->rootNamespace().'Policies';
        $path = $this->getPath($namespace.'\\'.$className);

        if (! $dryRun) {
            $this->ensureDirectoryExists(dirname($path));
            $stub = File::get(__DIR__.'/../../stubs/exportable_policy.stub');
            $exportClass = Str::studly($name).'Export';
            $content = str_replace(
                [
                    '{{ namespace }}',
                    '{{ class }}',
                    '{{ exportableNamespace }}',
                    '{{ exportableClass }}',
                ],
                [
                    $namespace,
                    $className,
                    $this->rootNamespace().'Exports',
                    $exportClass,
                ],
                $stub
            );
            File::put($path, $content);
        }

        return $path;
    }

    /**
     * Create the Livewire component
     */
    protected function createLivewire(string $name, bool $dryRun): string
    {
        $className = 'Export'.Str::studly($name);
        $namespace = $this->rootNamespace().'Livewire';
        $path = $this->getPath($namespace.'\\'.$className);
        $viewName = 'export-'.Str::kebab($name);

        if (! $dryRun) {
            $this->ensureDirectoryExists(dirname($path));
            $stub = File::get(__DIR__.'/../../stubs/exportable_livewire.stub');
            $factoryClass = Str::studly($name).'ExportFactory';
            $content = str_replace(
                [
                    '{{ namespace }}',
                    '{{ class }}',
                    '{{ factoryNamespace }}',
                    '{{ factoryClass }}',
                    '{{ view }}',
                ],
                [
                    $namespace,
                    $className,
                    $this->rootNamespace().'ExportFactories',
                    $factoryClass,
                    $viewName,
                ],
                $stub
            );
            File::put($path, $content);

            // Create the view file
            $viewPath = resource_path('views/livewire/'.$viewName.'.blade.php');
            $this->ensureDirectoryExists(dirname($viewPath));
            File::put($viewPath, '<div><!-- TODO: Implement export view --></div>');
        }

        return $path;
    }

    /**
     * Create test files for the generated classes
     */
    protected function createTests(string $name, array $files, bool $dryRun): array
    {
        $testFiles = [];

        foreach ($files as $file) {
            $className = basename($file, '.php');
            $type = match (true) {
                str_ends_with($className, 'Export') => 'exportable',
                str_ends_with($className, 'ExportFactory') => 'exportable_factory',
                str_ends_with($className, 'ExportPolicy') => 'exportable_policy',
                default => 'exportable_livewire'
            };

            $testClassName = $className.'Test';
            $testPath = base_path('tests/Feature/'.str_replace('app/', '', dirname($file)).'/'.$testClassName.'.php');
            $testNamespace = 'Tests\\Feature\\'.str_replace('/', '\\', str_replace('app/', '', dirname($file)));

            if (! $dryRun) {
                $this->ensureDirectoryExists(dirname($testPath));
                $stub = File::get(__DIR__.'/../../stubs/'.$type.'_test.stub');

                $replacements = [
                    '{{ namespace }}' => $testNamespace,
                    '{{ class }}' => $testClassName,
                ];

                // Add specific replacements based on type
                switch ($type) {
                    case 'exportable':
                        $replacements['{{ exportableNamespace }}'] = $this->rootNamespace().'Exports';
                        $replacements['{{ exportableClass }}'] = Str::studly($name).'Export';
                        break;
                    case 'exportable_factory':
                        $replacements['{{ factoryNamespace }}'] = $this->rootNamespace().'ExportFactories';
                        $replacements['{{ factoryClass }}'] = Str::studly($name).'ExportFactory';
                        break;
                    case 'exportable_policy':
                        $replacements['{{ policyNamespace }}'] = $this->rootNamespace().'Policies';
                        $replacements['{{ policyClass }}'] = Str::studly($name).'ExportPolicy';
                        $replacements['{{ exportableNamespace }}'] = $this->rootNamespace().'Exports';
                        $replacements['{{ exportableClass }}'] = Str::studly($name).'Export';
                        break;
                    case 'exportable_livewire':
                        $replacements['{{ livewireNamespace }}'] = $this->rootNamespace().'Livewire';
                        $replacements['{{ livewireClass }}'] = 'Export'.Str::studly($name);
                        $replacements['{{ factoryNamespace }}'] = $this->rootNamespace().'ExportFactories';
                        $replacements['{{ factoryClass }}'] = Str::studly($name).'ExportFactory';
                        $replacements['{{ view }}'] = 'export-'.Str::kebab($name);
                        break;
                }

                $content = str_replace(
                    array_keys($replacements),
                    array_values($replacements),
                    $stub
                );
                File::put($testPath, $content);
            }

            $testFiles[] = $testPath;
        }

        return $testFiles;
    }

    /**
     * Get the console command options.
     */
    protected function getOptions(): array
    {
        return [
            ['factory', null, InputOption::VALUE_NONE, 'Create an export factory'],
            ['policy', null, InputOption::VALUE_NONE, 'Create a policy for the export factory'],
            ['livewire', null, InputOption::VALUE_NONE, 'Create Livewire component for the export'],
            ['dry-run', null, InputOption::VALUE_NONE, 'Will render the names of all them files to render without actually creating them'],
            ['test', null, InputOption::VALUE_NONE, 'Create appropriate tests'],
        ];
    }

    /**
     * Ensure the directory exists and create it if it doesn't
     */
    protected function ensureDirectoryExists(string $path): void
    {
        if (! File::isDirectory($path)) {
            File::makeDirectory($path, 0755, true);
        }
    }
}
