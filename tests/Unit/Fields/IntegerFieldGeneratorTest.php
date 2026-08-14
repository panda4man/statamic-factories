<?php

use Panda4man\StatamicFactories\Fields\IntegerFieldGenerator;
use Panda4man\StatamicFactories\Support\FactoryContext;

it('generates an integer', function () {
    $value = (new IntegerFieldGenerator)->generate(fieldSchemaOfType('integer'), new FactoryContext);

    expect($value)->toBeInt();
});

it('respects configured min and max bounds', function () {
    $schema = fieldSchemaOfType('integer', ['min' => 10, 'max' => 20]);

    for ($i = 0; $i < 25; $i++) {
        $value = (new IntegerFieldGenerator)->generate($schema, new FactoryContext);

        expect($value)->toBeInt()
            ->and($value)->toBeGreaterThanOrEqual(10)
            ->and($value)->toBeLessThanOrEqual(20);
    }
});
