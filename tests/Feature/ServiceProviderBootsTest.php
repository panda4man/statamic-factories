<?php

use Panda4man\StatamicFactories\Fields\FieldGeneratorRegistry;

it('registers the field generator registry as a singleton', function () {
    $first = app(FieldGeneratorRegistry::class);
    $second = app(FieldGeneratorRegistry::class);

    expect($first)->toBeInstanceOf(FieldGeneratorRegistry::class)
        ->and($first)->toBe($second);
});

it('merges the package config with the default field generator map', function () {
    expect(config('statamic-factories.field_generators.text'))
        ->toBe(\Panda4man\StatamicFactories\Fields\TextFieldGenerator::class);
});
