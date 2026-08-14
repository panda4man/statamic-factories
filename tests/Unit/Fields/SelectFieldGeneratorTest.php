<?php

use Panda4man\StatamicFactories\Fields\SelectFieldGenerator;
use Panda4man\StatamicFactories\Support\FactoryContext;

it('returns the key for assoc options', function () {
    $schema = fieldSchemaOfType('select', ['options' => ['a' => 'Alpha', 'b' => 'Beta']]);

    for ($i = 0; $i < 25; $i++) {
        $value = (new SelectFieldGenerator)->generate($schema, new FactoryContext);

        expect($value)->toBeIn(['a', 'b']);
    }
});

it('returns the value for flat list options', function () {
    $schema = fieldSchemaOfType('select', ['options' => ['a', 'b']]);

    $value = (new SelectFieldGenerator)->generate($schema, new FactoryContext);

    expect($value)->toBeIn(['a', 'b']);
});

it('returns the key for pair-list options', function () {
    $schema = fieldSchemaOfType('select', [
        'options' => [
            ['key' => 'a', 'value' => 'Alpha'],
            ['key' => 'b', 'value' => 'Beta'],
        ],
    ]);

    $value = (new SelectFieldGenerator)->generate($schema, new FactoryContext);

    expect($value)->toBeIn(['a', 'b']);
});

it('returns an array of unique keys when multiple is true', function () {
    $schema = fieldSchemaOfType('select', ['options' => ['a', 'b', 'c'], 'multiple' => true]);

    for ($i = 0; $i < 25; $i++) {
        $value = (new SelectFieldGenerator)->generate($schema, new FactoryContext);

        expect($value)->toBeArray();
        expect($value)->toEqual(array_unique($value));
        foreach ($value as $item) {
            expect($item)->toBeIn(['a', 'b', 'c']);
        }
    }
});

it('returns a scalar when multiple is not set', function () {
    $schema = fieldSchemaOfType('select', ['options' => ['a', 'b', 'c']]);

    $value = (new SelectFieldGenerator)->generate($schema, new FactoryContext);

    expect($value)->not->toBeArray();
});

it('respects max_items when multiple is true', function () {
    $schema = fieldSchemaOfType('select', ['options' => ['a', 'b', 'c'], 'multiple' => true, 'max_items' => 2]);

    for ($i = 0; $i < 25; $i++) {
        $value = (new SelectFieldGenerator)->generate($schema, new FactoryContext);

        expect($value)->toBeArray()
            ->and(count($value))->toBeGreaterThanOrEqual(1)
            ->and(count($value))->toBeLessThanOrEqual(2);
    }
});

it('returns null when no options are configured', function () {
    $schema = fieldSchemaOfType('select', ['options' => []]);

    $value = (new SelectFieldGenerator)->generate($schema, new FactoryContext);

    expect($value)->toBeNull();
});
