<?php

use Panda4man\StatamicFactories\Fields\DateFieldGenerator;
use Panda4man\StatamicFactories\Support\FactoryContext;

it('defaults to Y-m-d H:i format', function () {
    $schema = fieldSchemaOfType('date');

    $value = (new DateFieldGenerator)->generate($schema, new FactoryContext);

    $parsed = DateTime::createFromFormat('Y-m-d H:i', $value);

    expect($parsed)->not->toBeFalse();
    expect($parsed->format('Y-m-d H:i'))->toBe($value);
});

it('respects a configured format', function () {
    $schema = fieldSchemaOfType('date', ['format' => 'Y-m-d']);

    $value = (new DateFieldGenerator)->generate($schema, new FactoryContext);

    $parsed = DateTime::createFromFormat('Y-m-d', $value);

    expect($parsed)->not->toBeFalse();
    expect($parsed->format('Y-m-d'))->toBe($value);
});

it('respects earliest and latest date bounds', function () {
    $schema = fieldSchemaOfType('date', [
        'format' => 'Y-m-d',
        'earliest_date' => '2020-01-01',
        'latest_date' => '2020-12-31',
    ]);

    for ($i = 0; $i < 25; $i++) {
        $value = (new DateFieldGenerator)->generate($schema, new FactoryContext);

        expect($value)->toBeGreaterThanOrEqual('2020-01-01')
            ->and($value)->toBeLessThanOrEqual('2020-12-31');
    }
});

it('returns a start/end array in range mode', function () {
    $schema = fieldSchemaOfType('date', [
        'format' => 'Y-m-d',
        'mode' => 'range',
        'earliest_date' => '2020-01-01',
        'latest_date' => '2020-12-31',
    ]);

    $value = (new DateFieldGenerator)->generate($schema, new FactoryContext);

    expect($value)->toBeArray();
    expect(array_keys($value))->toEqualCanonicalizing(['start', 'end']);
    expect($value['start'])->toBeString();
    expect($value['end'])->toBeString();
    expect($value['end'])->toBeGreaterThanOrEqual($value['start']);
});
