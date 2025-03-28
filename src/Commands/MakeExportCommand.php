<?php

namespace BinaryCats\Exportify\Commands;

use Illuminate\Console\Concerns\CreatesMatchingTest;
use Illuminate\Console\GeneratorCommand;

class MakeExportCommand extends GeneratorCommand
{
    use CreatesMatchingTest;

    /**
     * The console command name.
     *
     * @var string
     */
    protected $signature = 'make:exportify {name}
        {--policy : Create a policy for the export factory}';

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
    protected $type = 'Exportable';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        dd(parent::handle());

        if (parent::handle() === false) {
            return self::FAILURE;
        }

        if ($this->option('policy')) {

            $policy = str('Policy')
                ->prepend($this->argument('name'))
                ->value();

            $this->call('make:policy', [
                'name' => $policy,
                '--model' => $this->argument('name'),
            ]);
        }

        return self::SUCCESS;
    }

    /**
     * Get the stub file for the generator.
     */
    protected function getStub(): string
    {
        return __DIR__.'/stubs/exportable.stub';
    }

    /**
     * Get the default namespace for the class.
     */
    protected function getDefaultNamespace($rootNamespace): string
    {
        return $rootNamespace.'\\Exportables';
    }
}
