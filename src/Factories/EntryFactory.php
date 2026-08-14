<?php

namespace Panda4man\StatamicFactories\Factories;

use Illuminate\Support\Collection as SupportCollection;
use LogicException;
use Panda4man\StatamicFactories\Blueprints\BlueprintInspector;
use Panda4man\StatamicFactories\Fields\FieldGeneratorRegistry;
use Panda4man\StatamicFactories\Persistence\EntryPersister;
use Panda4man\StatamicFactories\Support\FactoryContext;
use Statamic\Contracts\Entries\Entry as EntryContract;
use Statamic\Facades\Blueprint as BlueprintFacade;
use Statamic\Facades\Collection;
use Statamic\Facades\Entry;
use Statamic\Fields\Blueprint;
use Statamic\Support\Str;

class EntryFactory extends Factory
{
    /**
     * Set by ::collection(), takes precedence over $collection.
     */
    protected ?string $collectionHandle = null;

    /**
     * Overridable by class-based factory subclasses as an alternative to ::collection().
     */
    protected ?string $collection = null;

    protected ?Blueprint $blueprint = null;

    public static function collection(string $handle): static
    {
        $factory = new static;
        $factory->collectionHandle = $handle;

        return $factory;
    }

    protected function collectionHandle(): string
    {
        if (! $handle = $this->collectionHandle ?? $this->collection) {
            throw new LogicException('No collection specified. Use ::collection() or declare a $collection property on your factory class.');
        }

        return $handle;
    }

    public function blueprint(Blueprint $blueprint): static
    {
        $factory = clone $this;
        $factory->blueprint = $blueprint;

        return $factory;
    }

    public function make(array $attributes = []): mixed
    {
        if ($this->count > 1) {
            return SupportCollection::times($this->count, fn () => $this->makeOne($attributes));
        }

        return $this->makeOne($attributes);
    }

    public function create(array $attributes = []): mixed
    {
        $persister = app(EntryPersister::class);

        if ($this->count > 1) {
            return SupportCollection::times($this->count, fn () => $persister->persist($this->makeOne($attributes)));
        }

        return $persister->persist($this->makeOne($attributes));
    }

    protected function makeOne(array $attributes): EntryContract
    {
        $blueprint = $this->resolvedBlueprint();
        $schema = (new BlueprintInspector)->inspect($blueprint);
        $registry = app(FieldGeneratorRegistry::class);
        $context = new FactoryContext(collectionHandle: $this->collectionHandle(), blueprintHandle: $blueprint->handle());

        $base = [];
        foreach ($schema->fields() as $handle => $field) {
            $base[$handle] = $field->default ?? $registry->resolve($field->type)?->generate($field, $context);
        }

        $data = array_merge($base, $this->definition());
        $data = $this->resolveStates($data);
        $data = array_merge($data, $attributes);

        $slug = $data['slug'] ?? (isset($data['title']) ? Str::slug($data['title']) : null);
        unset($data['slug']);

        $entry = Entry::make()
            ->collection($this->collectionHandle())
            ->blueprint($blueprint)
            ->data($data);

        if ($slug) {
            $entry->slug($slug);
        }

        return $entry;
    }

    protected function resolvedBlueprint(): Blueprint
    {
        if ($this->blueprint) {
            return $this->blueprint;
        }

        $handle = $this->collectionHandle();

        if (! Collection::findByHandle($handle)) {
            throw new LogicException("No blueprint could be resolved for collection [{$handle}]. Pass one explicitly via ->blueprint().");
        }

        // Deliberately resolved directly via Blueprint::in() rather than
        // Collection::entryBlueprint()/entryBlueprints(): calling the
        // collection's ensureEntryBlueprintFields() on a freshly file-loaded
        // blueprint resets its Blink content cache before the file contents
        // are ever lazily hydrated, silently truncating the blueprint down
        // to just the injected title/slug fields. We don't need that
        // auto-injection here — title/slug handling is done ourselves.
        $blueprints = BlueprintFacade::in('collections/'.$handle);
        $blueprint = $blueprints->reject->hidden()->first() ?? $blueprints->first();

        if (! $blueprint) {
            throw new LogicException("No blueprint could be resolved for collection [{$handle}]. Pass one explicitly via ->blueprint().");
        }

        return $blueprint;
    }
}
