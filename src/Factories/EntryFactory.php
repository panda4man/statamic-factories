<?php

namespace Panda4man\StatamicFactories\Factories;

use Illuminate\Support\Collection as SupportCollection;
use LogicException;
use Panda4man\StatamicFactories\Blueprints\BlueprintInspector;
use Panda4man\StatamicFactories\Blueprints\FieldSchema;
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
        if ($this->countExplicit) {
            $usedSlugs = [];

            return SupportCollection::times(max($this->count, 0), function () use ($attributes, &$usedSlugs) {
                return $this->makeOne($attributes, $usedSlugs);
            });
        }

        return $this->makeOne($attributes);
    }

    public function create(array $attributes = []): mixed
    {
        $persister = app(EntryPersister::class);

        if ($this->countExplicit) {
            $usedSlugs = [];

            return SupportCollection::times(max($this->count, 0), function () use ($attributes, &$usedSlugs, $persister) {
                return $persister->persist($this->makeOne($attributes, $usedSlugs));
            });
        }

        return $persister->persist($this->makeOne($attributes));
    }

    /**
     * @param  array<int, string>  $usedSlugs  Slugs already produced in this
     *                                         count() batch, passed by reference so
     *                                         colliding slugs (e.g. a fixed explicit
     *                                         title across count(n)) get disambiguated
     *                                         instead of silently overwriting each
     *                                         other on save.
     */
    protected function makeOne(array $attributes, array &$usedSlugs = []): EntryContract
    {
        $blueprint = $this->resolvedBlueprint();
        $schema = (new BlueprintInspector)->inspect($blueprint);
        $registry = app(FieldGeneratorRegistry::class);
        $context = new FactoryContext(collectionHandle: $this->collectionHandle(), blueprintHandle: $blueprint->handle());

        $base = [];
        foreach ($schema->fields() as $handle => $field) {
            if ($field->default === null && $registry->shouldSkip($field->type)) {
                continue;
            }

            $generated = $registry->resolve($field->type)?->generate($field, $context);
            $base[$handle] = $generated ?? $field->default;
        }

        // Statamic auto-injects a title field into every collection blueprint's
        // published fields; resolvedBlueprint() deliberately bypasses that
        // auto-injection (see its comment), so a blueprint that never declares
        // title explicitly would otherwise silently produce a title-less,
        // slug-less entry. Synthesize it directly through the same generator
        // path used for a declared field.
        if (! array_key_exists('title', $base)) {
            $titleField = new FieldSchema(handle: 'title', type: 'text', required: true, default: null, rules: [], config: []);
            $base['title'] = $registry->resolve('text')?->generate($titleField, $context);
        }

        $data = array_merge($base, $this->definition());
        $data = $this->resolveStates($data, $attributes);
        $data = array_merge($data, $attributes);

        $missing = $schema->fields()
            ->filter(fn (FieldSchema $field) => $field->required && (! array_key_exists($field->handle, $data) || $data[$field->handle] === null))
            ->keys();

        if ($missing->isNotEmpty()) {
            throw new RequiredFieldMissingException($blueprint->handle(), $missing->all());
        }

        $explicitSlug = array_key_exists('slug', $data) ? $data['slug'] : null;
        $slug = ($explicitSlug !== null && $explicitSlug !== '')
            ? $explicitSlug
            : (isset($data['title']) ? Str::slug($data['title']) : null);
        unset($data['slug']);

        if ($slug !== null && $slug !== '') {
            $original = $slug;
            $suffix = 2;

            while (in_array($slug, $usedSlugs, true)) {
                $slug = $original.'-'.$suffix;
                $suffix++;
            }

            $usedSlugs[] = $slug;
        }

        $published = array_key_exists('published', $data)
            ? (bool) $data['published']
            : config('statamic-factories.default_published', true);
        unset($data['published']);

        $entry = Entry::make()
            ->collection($this->collectionHandle())
            ->blueprint($blueprint)
            ->data($data);

        if ($slug !== null && $slug !== '') {
            $entry->slug($slug);
        }

        $entry->published($published);

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
