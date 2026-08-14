<?php

use Panda4man\StatamicFactories\Fields\FloatFieldGenerator;
use Panda4man\StatamicFactories\Support\FactoryContext;

it('generates a float with no config', function () {
    $value = (new FloatFieldGenerator)->generate(fieldSchemaOfType('float'), new FactoryContext);

    expect($value)->toBeFloat();
});

it('respects configured min and max bounds', function () {
    $schema = fieldSchemaOfType('float', ['min' => 2.5, 'max' => 3.5]);

    for ($i = 0; $i < 25; $i++) {
        $value = (new FloatFieldGenerator)->generate($schema, new FactoryContext);

        expect($value)->toBeFloat()
            ->and($value)->toBeGreaterThanOrEqual(2.5)
            ->and($value)->toBeLessThanOrEqual(3.5);
    }
});

it('does not throw when min and max are inverted', function () {
    $schema = fieldSchemaOfType('float', ['min' => 10, 'max' => 1]);

    $value = (new FloatFieldGenerator)->generate($schema, new FactoryContext);

    expect($value)->toBeFloat()
        ->and($value)->toBeGreaterThanOrEqual(1)
        ->and($value)->toBeLessThanOrEqual(10);
});
