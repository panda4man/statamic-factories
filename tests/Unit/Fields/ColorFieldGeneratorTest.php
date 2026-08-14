<?php

use Panda4man\StatamicFactories\Fields\ColorFieldGenerator;
use Panda4man\StatamicFactories\Support\FactoryContext;

it('generates a hex color with no config', function () {
    $schema = fieldSchemaOfType('color');

    $value = (new ColorFieldGenerator)->generate($schema, new FactoryContext);

    expect($value)->toMatch('/^#[0-9a-f]{6}$/i');
});

it('returns a configured swatch', function () {
    $schema = fieldSchemaOfType('color', ['swatches' => ['#ff0000', '#00ff00']]);

    for ($i = 0; $i < 25; $i++) {
        $value = (new ColorFieldGenerator)->generate($schema, new FactoryContext);

        expect($value)->toBeIn(['#ff0000', '#00ff00']);
    }
});

it('falls back to a generated hex color when swatches is empty', function () {
    $schema = fieldSchemaOfType('color', ['swatches' => []]);

    $value = (new ColorFieldGenerator)->generate($schema, new FactoryContext);

    expect($value)->toMatch('/^#[0-9a-f]{6}$/i');
});
