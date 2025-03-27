<?php

namespace BinaryCats\Exportify\Concerns;

use Illuminate\Support\Arr;

trait InteractsWithArguments
{
    /** @var mixed[] */
    protected array $arguments = [];

    /**
     * Get an argument.
     */
    public function argument(string $key, mixed $default = null): mixed
    {
        return Arr::get($this->arguments, $key, $default);
    }

    /**
     * Set the arguments.
     *
     * You can use it to both set the arguments and get a value
     *     If you provide the array to the method, it will merge
     *     with the existing arguments and return instance
     *
     *     If you provide the string $value, we will attempt to find
     *     the argument by its key, else, default
     */
    public function arguments(array|string $value = null, $default = null): mixed
    {
        if (is_array($value)) {
            $this->arguments = array_merge($this->arguments, $value);

            return $this;
        }

        if (is_string($value)) {
            return $this->argument($value, $default);
        }

        return $this->arguments;
    }
}
