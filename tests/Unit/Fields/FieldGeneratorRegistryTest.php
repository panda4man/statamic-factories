<?php

use Panda4man\StatamicFactories\Blueprints\FieldSchema;
use Panda4man\StatamicFactories\Contracts\FieldGenerator;
use Panda4man\StatamicFactories\Fields\FieldGeneratorRegistry;
use Panda4man\StatamicFactories\Fields\UnsupportedFieldtypeException;
use Panda4man\StatamicFactories\Support\FactoryContext;

function dummyFieldSchema(string $type = 'dummy'): FieldSchema
{
    return new FieldSchema(
        handle: 'field',
        type: $type,
        required: false,
        default: null,
        rules: [],
        config: [],
    );
}

it('resolves a registered generator by fieldtype', function () {
    $generator = new class implements FieldGenerator
    {
        public function generate(FieldSchema $field, FactoryContext $context): mixed
        {
            return 'generated';
        }
    };

    $registry = app(FieldGeneratorRegistry::class);
    $registry->register('dummy', $generator);

    $resolved = $registry->resolve('dummy');

    expect($resolved)->toBe($generator)
        ->and($resolved->generate(dummyFieldSchema(), new FactoryContext))->toBe('generated');
});

it('resolves a registered generator from a class string via the container', function () {
    $generatorClass = new class implements FieldGenerator
    {
        public function generate(FieldSchema $field, FactoryContext $context): mixed
        {
            return 'from-container';
        }
    };

    $registry = app(FieldGeneratorRegistry::class);
    $registry->register('dummy-class', $generatorClass::class);

    expect($registry->resolve('dummy-class'))->toBeInstanceOf(FieldGenerator::class);
});

it('returns null for an unsupported fieldtype by default', function () {
    config(['statamic-factories.unsupported_fieldtype_behavior' => 'null']);

    $registry = app(FieldGeneratorRegistry::class);

    expect($registry->resolve('unknown_type'))->toBeNull();
});

it('throws for an unsupported fieldtype when configured to throw', function () {
    config(['statamic-factories.unsupported_fieldtype_behavior' => 'throw']);

    $registry = app(FieldGeneratorRegistry::class);

    expect(fn () => $registry->resolve('unknown_type'))
        ->toThrow(UnsupportedFieldtypeException::class);
});
