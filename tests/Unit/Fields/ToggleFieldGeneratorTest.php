<?php

use Panda4man\StatamicFactories\Fields\ToggleFieldGenerator;
use Panda4man\StatamicFactories\Support\FactoryContext;

it('generates a boolean', function () {
    $value = (new ToggleFieldGenerator)->generate(fieldSchemaOfType('toggle'), new FactoryContext);

    expect($value)->toBeBool();
});

it('is capable of producing both true and false, proving it is not hardcoded', function () {
    $schema = fieldSchemaOfType('toggle');
    $values = [];

    foreach (range(1, 50) as $seed) {
        fake()->seed($seed);
        $values[] = (new ToggleFieldGenerator)->generate($schema, new FactoryContext);
    }

    expect($values)->toContain(true)->toContain(false);
});
