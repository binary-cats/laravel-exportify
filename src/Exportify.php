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
     */
    public function all(): ExportableCollection
    {
        return new ExportableCollection($this->exports);
    }

    /**
     * Get all available exports for the current user.
     */
    public function available(): ExportableCollection
    {
        return $this->all()
            ->filter(function (Exportable $exportable, string $name) {
                if (Gate::getPolicyFor($name)) {
                    return Gate::allows(config('exportify.policy.name', 'view'), $name);
                }

                return config('exportify.policy.default', false);
            });
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
    public function find(string $name): ?Exportable
    {
        throw_unless(
            Arr::has($this->exports, $name),
            ExportifyException::missingFactory($name)
        );

        return $this->exports[$name];
    }

    /**
     * Register a new export.
     */
    public function register(string $name, Exportable $exportable): void
    {
        $this->exports[$name] = $exportable;
    }

    /**
     * Unregister an export.
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
