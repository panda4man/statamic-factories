<?php

use Panda4man\StatamicFactories\Fields\YamlFieldGenerator;
use Panda4man\StatamicFactories\Support\FactoryContext;

it('generates an array with the expected keys', function () {
    $value = (new YamlFieldGenerator)->generate(fieldSchemaOfType('yaml'), new FactoryContext);

    expect($value)->toBeArray()
        ->toHaveKeys(['title', 'enabled', 'count']);
});

it('generates values of the expected types', function () {
    $value = (new YamlFieldGenerator)->generate(fieldSchemaOfType('yaml'), new FactoryContext);

    expect($value['title'])->toBeString()
        ->and($value['enabled'])->toBeBool()
        ->and($value['count'])->toBeInt();
});
