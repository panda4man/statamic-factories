<?php

use Panda4man\StatamicFactories\Fields\FieldGeneratorRegistry;

it('reports shouldSkip true only when the fieldtype is unsupported and behavior is skip', function () {
    config(['statamic-factories.unsupported_fieldtype_behavior' => 'skip']);

    $registry = app(FieldGeneratorRegistry::class);

    expect($registry->shouldSkip('unknown_type'))->toBeTrue()
        ->and($registry->shouldSkip('text'))->toBeFalse();
});

it('reports shouldSkip false when behavior is null or throw', function () {
    config(['statamic-factories.unsupported_fieldtype_behavior' => 'null']);
    $registry = app(FieldGeneratorRegistry::class);
    expect($registry->shouldSkip('unknown_type'))->toBeFalse();

    config(['statamic-factories.unsupported_fieldtype_behavior' => 'throw']);
    expect($registry->shouldSkip('unknown_type'))->toBeFalse();
});
