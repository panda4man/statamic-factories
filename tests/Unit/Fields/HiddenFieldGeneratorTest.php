<?php

use Panda4man\StatamicFactories\Fields\HiddenFieldGenerator;
use Panda4man\StatamicFactories\Support\FactoryContext;

it('returns the configured default', function () {
    $schema = fieldSchemaOfType('hidden', ['default' => 'abc']);

    $value = (new HiddenFieldGenerator)->generate($schema, new FactoryContext);

    expect($value)->toBe('abc');
});

it('returns null when no default is configured', function () {
    $schema = fieldSchemaOfType('hidden');

    $value = (new HiddenFieldGenerator)->generate($schema, new FactoryContext);

    expect($value)->toBeNull();
});
