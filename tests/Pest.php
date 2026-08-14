<?php

use Panda4man\StatamicFactories\Blueprints\FieldSchema;
use Tests\TestCase;

uses(TestCase::class)->in('Feature', 'Unit');

function fieldSchemaOfType(string $type, array $config = []): FieldSchema
{
    return new FieldSchema(
        handle: 'field',
        type: $type,
        required: false,
        default: null,
        rules: [],
        config: $config,
    );
}
