<?php

namespace Panda4man\StatamicFactories\Factories;

use Closure;
use Panda4man\StatamicFactories\Contracts\Factory as FactoryContract;

abstract class Factory implements FactoryContract
{
    protected int $count = 1;

    /** @var array<int, array|Closure> */
    protected array $states = [];

    public static function new(array $attributes = []): static
    {
        $factory = new static;

        return $attributes === [] ? $factory : $factory->state($attributes);
    }

    public function count(int $count): static
    {
        $factory = clone $this;
        $factory->count = $count;

        return $factory;
    }

    public function state(array|Closure $state): static
    {
        $factory = clone $this;
        $factory->states[] = $state;

        return $factory;
    }

    public function definition(): array
    {
        return [];
    }

    /**
     * Compose all applied state() calls, in order, against the given attributes.
     */
    protected function resolveStates(array $attributes = []): array
    {
        return array_reduce(
            $this->states,
            fn (array $carry, array|Closure $state) => array_merge(
                $carry,
                $state instanceof Closure ? $state($carry) : $state,
            ),
            $attributes,
        );
    }

    abstract public function make(array $attributes = []): mixed;

    abstract public function create(array $attributes = []): mixed;
}
