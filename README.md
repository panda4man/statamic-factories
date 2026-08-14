# Statamic Factories

Laravel-Eloquent-style factories for [Statamic](https://statamic.com) content. If you've used `Model::factory()->create()` in a Laravel app, this is the same idea, pointed at Statamic collections instead of database models.

Statamic Factories reads a collection's blueprint, generates a valid value for every field with Faker, and lets you override whatever you care about. No more hand-building entry data for every test or seed script.

```php
$entry = EntryFactory::collection('services')->create();
```

That single line resolves the `services` collection's blueprint, generates realistic data for every field (respecting blueprint defaults, required fields, and fieldtype semantics), builds a Statamic entry, and persists it through Statamic's own APIs.

## Status

This package is early and currently covers one vertical slice: collection entries, with support for `text`, `textarea`, `integer`, and `toggle` fieldtypes. That slice is fully built and tested end to end: `make()`/`create()`, `count()`, `state()`, class-based factories, blueprint auto-resolution, title/slug handling, deterministic Faker seeding.

Not yet built: term/global/user factories, entry/asset/term relationships, Bard/Replicator/Grid/Group fieldtypes, sequences, and lifecycle callbacks. The architecture (blueprint inspection, a swappable field generator registry, separate generation/persistence layers) is meant to grow into those without a rewrite, but they aren't there yet. It's a foundation, not a finished 1.0.

## Requirements

- PHP 8.2+
- Laravel 11, 12, or 13
- Statamic 5 or 6

## Installation

```bash
composer require panda4man/statamic-factories
```

Statamic auto-discovers the service provider. If you want to customize the field generator map or how unsupported fieldtypes are handled, publish the config:

```bash
php artisan vendor:publish --tag=statamic-factories-config
```

## Usage

### Basic generation

```php
use Panda4man\StatamicFactories\Factories\EntryFactory;

// Persist a single entry, blueprint auto-resolved from the collection
$entry = EntryFactory::collection('services')->create();

// Build an entry in memory without touching storage
$entry = EntryFactory::collection('services')->make();

// Generate several at once
$entries = EntryFactory::collection('services')->count(10)->create();
```

`make()` never persists anything: no Stache writes, no side effects. `create()` does the same generation work and then saves the result through Statamic's own `Entry::save()`.

### Overriding values

Anything you pass explicitly always wins over generated data:

```php
$entry = EntryFactory::collection('services')->create([
    'title' => 'Landscaping',
    'featured' => true,
]);
```

`state()` lets you compose reusable overrides before the final call, and still loses to an explicit argument passed to `create()`/`make()`:

```php
$entries = EntryFactory::collection('services')
    ->state(['featured' => true])
    ->count(5)
    ->create();
```

### Blueprints

By default, the factory resolves the collection's own blueprint automatically. You can hand it an explicit blueprint instead, which is useful in tests where you don't want to depend on a real collection's configuration:

```php
use Statamic\Facades\Blueprint;

$blueprint = Blueprint::makeFromFields([
    'title' => ['type' => 'text', 'required' => true],
    'duration' => ['type' => 'integer', 'default' => 30],
]);

$entry = EntryFactory::collection('services')->blueprint($blueprint)->make();
```

### Titles and slugs

If a `title` is present and no `slug` was explicitly supplied, one is derived automatically:

```php
$entry = EntryFactory::collection('services')->create(['title' => 'My Example Service']);

$entry->slug(); // 'my-example-service'
```

Pass `slug` explicitly and it wins over the derived value.

### Class-based factories

For anything you'll reuse across tests, extend `EntryFactory` the same way you'd extend Laravel's `Factory`:

```php
use Panda4man\StatamicFactories\Factories\EntryFactory;

class ServiceFactory extends EntryFactory
{
    protected ?string $collection = 'services';

    public function definition(): array
    {
        return [
            'featured' => false,
        ];
    }

    public function featured(): static
    {
        return $this->state(['featured' => true]);
    }
}
```

```php
$service = ServiceFactory::new()
    ->featured()
    ->create(['title' => 'My Test Service']);
```

### Determinism

Field generation goes through Laravel's shared `fake()` Faker instance, so seeding it in a test seeds factory output too:

```php
fake()->seed(1234);

$entry = EntryFactory::collection('services')->create();
```

### Value precedence

For every field, the factory resolves a value in this order, each step overriding the one before it:

1. The blueprint's configured default, if one exists
2. A generated value from the field's `FieldGenerator`
3. Whatever `definition()` returns on a class-based factory
4. Anything applied through `state()`
5. An explicit attribute passed to `create()`/`make()`

### Custom field generators

Every fieldtype is handled by a small `FieldGenerator` class registered in `FieldGeneratorRegistry`. To support a fieldtype this package doesn't know about, implement the contract and register it:

```php
use Panda4man\StatamicFactories\Contracts\FieldGenerator;
use Panda4man\StatamicFactories\Blueprints\FieldSchema;
use Panda4man\StatamicFactories\Support\FactoryContext;
use Panda4man\StatamicFactories\Fields\FieldGeneratorRegistry;

class ColorFieldGenerator implements FieldGenerator
{
    public function generate(FieldSchema $field, FactoryContext $context): mixed
    {
        return $context->faker()->hexColor();
    }
}

app(FieldGeneratorRegistry::class)->register('color', ColorFieldGenerator::class);
```

Or add it to the published config's `field_generators` array to have it registered on every boot.

By default, a field with no registered generator resolves to `null`. Set `unsupported_fieldtype_behavior` to `throw` in the config if you'd rather fail loudly than silently generate `null` for a fieldtype you haven't handled yet.

## Testing

The package's own suite is written in [Pest](https://pestphp.com) against Statamic's `AddonTestCase`, and uses `PreventsSavingStacheItemsToDisk` so nothing it does touches your working tree.

```bash
composer test
```

If you're writing tests in a consuming app, the same trait is worth using alongside this package so your own `create()` calls stay off disk too:

```php
use Statamic\Testing\Concerns\PreventsSavingStacheItemsToDisk;

class MyTest extends TestCase
{
    use PreventsSavingStacheItemsToDisk;
}
```

## License

MIT
