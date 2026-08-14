<?php

use Panda4man\StatamicFactories\Fields\TextFieldGenerator;
use Panda4man\StatamicFactories\Support\FactoryContext;

it('generates a non-empty string', function () {
    fake()->seed(1234);

    $value = (new TextFieldGenerator)->generate(fieldSchemaOfType('text'), new FactoryContext);

    expect($value)->toBeString()->not->toBeEmpty();
});

it('is deterministic for a given seed', function () {
    $schema = fieldSchemaOfType('text');

    fake()->seed(1234);
    $first = (new TextFieldGenerator)->generate($schema, new FactoryContext);

    fake()->seed(1234);
    $second = (new TextFieldGenerator)->generate($schema, new FactoryContext);

    expect($first)->toBe($second);
});
