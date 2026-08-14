<?php

use Panda4man\StatamicFactories\Fields\CodeFieldGenerator;
use Panda4man\StatamicFactories\Support\FactoryContext;

it('defaults to the htmlmixed mode when mode selectable', function () {
    $schema = fieldSchemaOfType('code');

    $value = (new CodeFieldGenerator)->generate($schema, new FactoryContext);

    expect($value)->toBeArray()
        ->and($value['code'])->toBeString()
        ->and($value['mode'])->toBe('htmlmixed');
});

it('uses the configured mode when mode selectable', function () {
    $schema = fieldSchemaOfType('code', ['mode' => 'php']);

    $value = (new CodeFieldGenerator)->generate($schema, new FactoryContext);

    expect($value['mode'])->toBe('php');
});

it('returns a plain string when mode is not selectable', function () {
    $schema = fieldSchemaOfType('code', ['mode_selectable' => false]);

    $value = (new CodeFieldGenerator)->generate($schema, new FactoryContext);

    expect($value)->toBeString();
});

it('still returns a plain string when mode is not selectable, regardless of configured mode', function () {
    $schema = fieldSchemaOfType('code', ['mode_selectable' => false, 'mode' => 'php']);

    $value = (new CodeFieldGenerator)->generate($schema, new FactoryContext);

    expect($value)->toBeString();
});
