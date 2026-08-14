<?php

namespace Panda4man\StatamicFactories\Factories;

use LogicException;
use Panda4man\StatamicFactories\Blueprints\BlueprintInspector;
use Panda4man\StatamicFactories\Fields\FieldGeneratorRegistry;
use Panda4man\StatamicFactories\Support\FactoryContext;
use Statamic\Contracts\Entries\Entry as EntryContract;
use Statamic\Facades\Entry;
use Statamic\Fields\Blueprint;

class EntryFactory extends Factory
{
    protected ?string $collectionHandle = null;

    protected ?Blueprint $blueprint = null;

    public static function collection(string $handle): static
    {
        $factory = new static;
        $factory->collectionHandle = $handle;

        return $factory;
    }

    public function blueprint(Blueprint $blueprint): static
    {
        $factory = clone $this;
        $factory->blueprint = $blueprint;

        return $factory;
    }

    public function make(array $attributes = []): mixed
    {
        return $this->makeOne($attributes);
    }

    public function create(array $attributes = []): mixed
    {
        throw new LogicException('EntryFactory::create() is not yet implemented.');
    }

    protected function makeOne(array $attributes): EntryContract
    {
        $blueprint = $this->resolvedBlueprint();
        $schema = (new BlueprintInspector)->inspect($blueprint);
        $registry = app(FieldGeneratorRegistry::class);
        $context = new FactoryContext(collectionHandle: $this->collectionHandle, blueprintHandle: $blueprint->handle());

        $base = [];
        foreach ($schema->fields() as $handle => $field) {
            $base[$handle] = $field->default ?? $registry->resolve($field->type)?->generate($field, $context);
        }

        $data = array_merge($base, $this->definition());
        $data = $this->resolveStates($data);
        $data = array_merge($data, $attributes);

        return Entry::make()
            ->collection($this->collectionHandle)
            ->blueprint($blueprint)
            ->data($data);
    }

    protected function resolvedBlueprint(): Blueprint
    {
        if ($this->blueprint) {
            return $this->blueprint;
        }

        throw new LogicException('No blueprint could be resolved. Pass one explicitly via ->blueprint(), or resolve automatically from the collection (see create()).');
    }
}
