<?php

use Panda4man\StatamicFactories\Fields\WidthFieldGenerator;
use Panda4man\StatamicFactories\Support\FactoryContext;

it('picks from configured flat list options', function () {
    $schema = fieldSchemaOfType('width', ['options' => [25, 33, 50]]);

    for ($i = 0; $i < 25; $i++) {
        $value = (new WidthFieldGenerator)->generate($schema, new FactoryContext);

        expect($value)->toBeIn([25, 33, 50]);
    }
});

it('falls back to the standard width options when none are configured', function () {
    $schema = fieldSchemaOfType('width');

    for ($i = 0; $i < 25; $i++) {
        $value = (new WidthFieldGenerator)->generate($schema, new FactoryContext);

        expect($value)->toBeIn([25, 33, 50, 66, 75, 100]);
    }
});

it('returns a key rather than a label for assoc options', function () {
    $schema = fieldSchemaOfType('width', ['options' => ['a' => '25%', 'b' => '50%']]);

    $value = (new WidthFieldGenerator)->generate($schema, new FactoryContext);

    expect($value)->toBeIn(['a', 'b']);
});
