<?php

use Panda4man\StatamicFactories\Blueprints\BlueprintInspector;
use Statamic\Facades\Blueprint;

it('normalizes a blueprint into an ordered field schema collection', function () {
    $blueprint = Blueprint::makeFromFields([
        'title' => ['type' => 'text', 'required' => true],
        'description' => ['type' => 'textarea'],
        'duration' => ['type' => 'integer'],
        'featured' => ['type' => 'toggle'],
    ]);

    $schema = (new BlueprintInspector)->inspect($blueprint);

    expect($schema->fields()->keys()->all())->toBe(['title', 'description', 'duration', 'featured'])
        ->and($schema->fields()->get('title')->type)->toBe('text')
        ->and($schema->fields()->get('description')->type)->toBe('textarea')
        ->and($schema->fields()->get('duration')->type)->toBe('integer')
        ->and($schema->fields()->get('featured')->type)->toBe('toggle');
});
