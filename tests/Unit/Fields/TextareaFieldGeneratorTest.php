<?php

use Panda4man\StatamicFactories\Fields\TextareaFieldGenerator;
use Panda4man\StatamicFactories\Support\FactoryContext;

it('generates a non-empty string', function () {
    fake()->seed(1234);

    $value = (new TextareaFieldGenerator)->generate(fieldSchemaOfType('textarea'), new FactoryContext);

    expect($value)->toBeString()->not->toBeEmpty();
});
