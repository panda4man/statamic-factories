<?php

use Panda4man\StatamicFactories\Blueprints\FieldSchema;
use Statamic\Fields\Field;

it('captures handle, type, and required from the field', function () {
    $field = new Field('title', ['type' => 'text', 'required' => true]);

    $schema = FieldSchema::fromField($field);

    expect($schema->handle)->toBe('title')
        ->and($schema->type)->toBe('text')
        ->and($schema->required)->toBeTrue();
});

it('does not fall back to the fieldtype default when no blueprint default is configured', function () {
    // Toggle's fieldtype default is `false` — FieldSchema must read the raw
    // blueprint config, not Field::defaultValue(), or every toggle field
    // would silently resolve to false and never reach the generator.
    $field = new Field('featured', ['type' => 'toggle']);

    $schema = FieldSchema::fromField($field);

    expect($schema->default)->toBeNull();
});

it('captures an explicit blueprint default', function () {
    $field = new Field('duration', ['type' => 'integer', 'default' => 5]);

    $schema = FieldSchema::fromField($field);

    expect($schema->default)->toBe(5);
});
