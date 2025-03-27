<?php

namespace BinaryCats\Exportify;

use BinaryCats\Exportify\Concerns\ExportableCollection;
use BinaryCats\Exportify\Contracts\ExportFactory;
use BinaryCats\Exportify\Exceptions\ExportifyException;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Gate;

class Exportify
{
    /**
     * The registered exports.
     *
     * @var array<string, ExportFactory>
     */
    protected array $exports = [];

    /**
     * Get all registered exports.
     *
     * @return ExportableCollection
     */
    public function all(): ExportableCollection
    {
        return new ExportableCollection($this->exports);
    }

    /**
     * Get all available exports for the current user.
     *
     * @return ExportableCollection
     */
    public function available(): ExportableCollection
    {
        return $this->all()
            ->filter(fn (ExportFactory $factory, string $name) => Gate::allows('view', $name));
    }

    /**
     * Get all exports with the given tags.
     */
    public function tagged(string|array $tags): ExportableCollection
    {
        return $this->all()->tagged($tags);
    }

    /**
     * Find an export by name.
     */
    public function find(string $name): ?ExportFactory
    {
        throw_unless(
            Arr::has($this->exports, $name), 
            ExportifyException::missingFactory($name)
        );

        return $this->exports[$name];
    }

    /**
     * Register a new export.
     *
     * @param  string  $name
     * @param  ExportFactory  $factory
     * @return void
     */
    public function register(string $name, ExportFactory $factory): void
    {
        $this->exports[$name] = $factory;
    }

    /**
     * Unregister an export.
     *
     * @param  string  $name
     * @return void
     */
    public function unregister(string $name): void
    {
        unset($this->exports[$name]);
    }

    /**
     * Flush all registered exports.
     */
    public function flush(): static
    {
        $this->exports = [];

        return $this;
    }
}
