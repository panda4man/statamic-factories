<?php

namespace Panda4man\StatamicFactories\Fields;

use Illuminate\Contracts\Container\Container;
use Panda4man\StatamicFactories\Contracts\FieldGenerator;

class FieldGeneratorRegistry
{
    protected array $generators = [];

    public function __construct(protected Container $container)
    {
    }

    public function register(string $type, FieldGenerator|string $generator): void
    {
        $this->generators[$type] = $generator;
    }

    public function has(string $type): bool
    {
        return isset($this->generators[$type]);
    }

    public function resolve(string $type): ?FieldGenerator
    {
        if (! $this->has($type)) {
            return $this->handleUnsupported($type);
        }

        $generator = $this->generators[$type];

        return is_string($generator) ? $this->container->make($generator) : $generator;
    }

    protected function handleUnsupported(string $type): ?FieldGenerator
    {
        if (config('statamic-factories.unsupported_fieldtype_behavior') === 'throw') {
            throw new UnsupportedFieldtypeException($type);
        }

        return null;
    }
}
