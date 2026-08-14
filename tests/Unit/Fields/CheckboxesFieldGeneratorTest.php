<?php

use Panda4man\StatamicFactories\Fields\CheckboxesFieldGenerator;
use Panda4man\StatamicFactories\Support\FactoryContext;

it('returns an array of unique keys', function () {
    $schema = fieldSchemaOfType('checkboxes', ['options' => ['a' => 'Alpha', 'b' => 'Beta', 'c' => 'Gamma']]);

    for ($i = 0; $i < 25; $i++) {
        $value = (new CheckboxesFieldGenerator)->generate($schema, new FactoryContext);

        expect($value)->toBeArray();
        expect($value)->toEqual(array_unique($value));
        foreach ($value as $item) {
            expect($item)->toBeIn(['a', 'b', 'c']);
        }
    }
});

it('produces varying counts between 1 and the option count', function () {
    $schema = fieldSchemaOfType('checkboxes', ['options' => ['a', 'b', 'c']]);
    $lengths = [];

    foreach (range(1, 50) as $seed) {
        fake()->seed($seed);
        $value = (new CheckboxesFieldGenerator)->generate($schema, new FactoryContext);
        $lengths[] = count($value);

        expect(count($value))->toBeGreaterThanOrEqual(1)->toBeLessThanOrEqual(3);
    }

    expect(count(array_unique($lengths)))->toBeGreaterThanOrEqual(2);
});

it('returns null when no options are configured', function () {
    $schema = fieldSchemaOfType('checkboxes', ['options' => []]);

    $value = (new CheckboxesFieldGenerator)->generate($schema, new FactoryContext);

    expect($value)->toBeNull();
});
