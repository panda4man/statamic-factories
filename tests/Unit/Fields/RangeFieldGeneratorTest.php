<?php

use Panda4man\StatamicFactories\Fields\RangeFieldGenerator;
use Panda4man\StatamicFactories\Support\FactoryContext;

it('generates an int within the default 0-100 range', function () {
    $schema = fieldSchemaOfType('range');

    for ($i = 0; $i < 25; $i++) {
        $value = (new RangeFieldGenerator)->generate($schema, new FactoryContext);

        expect($value)->toBeInt()
            ->and($value)->toBeGreaterThanOrEqual(0)
            ->and($value)->toBeLessThanOrEqual(100);
    }
});

it('snaps to the configured step', function () {
    $schema = fieldSchemaOfType('range', ['min' => 0, 'max' => 10, 'step' => 2]);

    for ($i = 0; $i < 25; $i++) {
        $value = (new RangeFieldGenerator)->generate($schema, new FactoryContext);

        expect($value)->toBeInt()
            ->and($value)->toBeGreaterThanOrEqual(0)
            ->and($value)->toBeLessThanOrEqual(10)
            ->and($value % 2)->toBe(0);
    }
});

it('produces a float when the step is a decimal', function () {
    $schema = fieldSchemaOfType('range', ['min' => 0, 'max' => 1, 'step' => 0.5]);

    for ($i = 0; $i < 25; $i++) {
        $value = (new RangeFieldGenerator)->generate($schema, new FactoryContext);

        expect($value)->toBeFloat()
            ->and($value)->toBeGreaterThanOrEqual(0)
            ->and($value)->toBeLessThanOrEqual(1);

        expect(fmod($value * 2, 1.0))->toBe(0.0);
    }
});

it('does not throw when min and max are inverted', function () {
    $schema = fieldSchemaOfType('range', ['min' => 10, 'max' => 1]);

    $value = (new RangeFieldGenerator)->generate($schema, new FactoryContext);

    expect($value)->toBe(10);
});
