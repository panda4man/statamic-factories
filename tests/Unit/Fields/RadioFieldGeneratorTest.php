<?php

use Panda4man\StatamicFactories\Fields\RadioFieldGenerator;
use Panda4man\StatamicFactories\Support\FactoryContext;

it('returns the key for assoc options', function () {
    $schema = fieldSchemaOfType('radio', ['options' => ['a' => 'Alpha', 'b' => 'Beta']]);

    for ($i = 0; $i < 25; $i++) {
        $value = (new RadioFieldGenerator)->generate($schema, new FactoryContext);

        expect($value)->toBeIn(['a', 'b']);
    }
});

it('returns the value for flat list options', function () {
    $schema = fieldSchemaOfType('radio', ['options' => ['a', 'b']]);

    $value = (new RadioFieldGenerator)->generate($schema, new FactoryContext);

    expect($value)->toBeIn(['a', 'b']);
});

it('is always scalar even when multiple is present', function () {
    $schema = fieldSchemaOfType('radio', ['options' => ['a', 'b', 'c'], 'multiple' => true]);

    $value = (new RadioFieldGenerator)->generate($schema, new FactoryContext);

    expect($value)->not->toBeArray();
});

it('returns null when no options are configured', function () {
    $schema = fieldSchemaOfType('radio', ['options' => []]);

    $value = (new RadioFieldGenerator)->generate($schema, new FactoryContext);

    expect($value)->toBeNull();
});
