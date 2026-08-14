<?php

use Panda4man\StatamicFactories\Fields\TimeFieldGenerator;
use Panda4man\StatamicFactories\Support\FactoryContext;

it('generates an H:i time by default', function () {
    $schema = fieldSchemaOfType('time');

    for ($i = 0; $i < 25; $i++) {
        $value = (new TimeFieldGenerator)->generate($schema, new FactoryContext);

        expect($value)->toMatch('/^\d{2}:\d{2}$/');
        expect(DateTime::createFromFormat('!H:i', $value)->format('H:i'))->toBe($value);
    }
});

it('generates an H:i:s time when seconds are enabled', function () {
    $schema = fieldSchemaOfType('time', ['seconds_enabled' => true]);

    for ($i = 0; $i < 25; $i++) {
        $value = (new TimeFieldGenerator)->generate($schema, new FactoryContext);

        expect($value)->toMatch('/^\d{2}:\d{2}:\d{2}$/');
        expect(DateTime::createFromFormat('!H:i:s', $value)->format('H:i:s'))->toBe($value);
    }
});
