<?php

namespace Panda4man\StatamicFactories\Factories;

use Closure;
use Panda4man\StatamicFactories\Contracts\Factory as FactoryContract;

abstract class Factory implements FactoryContract
{
    protected int $count = 1;

    /**
     * True once count() has been called explicitly — distinguishes count(1)
     * (explicit, must return a collection) from the default of never having
     * called count() at all (must return a bare entity).
     */
    protected bool $countExplicit = false;

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
        $factory->countExplicit = true;

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
     *
     * $explicit (the attributes passed to make()/create()) is exposed to each
     * closure so it can derive values from the caller's final overrides, but
     * a closure's return value still can't override $explicit — the caller
     * merges $explicit back on top after resolveStates() runs.
     */
    protected function resolveStates(array $attributes = [], array $explicit = []): array
    {
        return array_reduce(
            $this->states,
            fn (array $carry, array|Closure $state) => array_merge(
                $carry,
                $state instanceof Closure ? $state(array_merge($carry, $explicit)) : $state,
            ),
            $attributes,
        );
    }

    abstract public function make(array $attributes = []): mixed;

    abstract public function create(array $attributes = []): mixed;
}
