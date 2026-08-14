<?php

use Panda4man\StatamicFactories\Blueprints\FieldSchema;
use Statamic\Facades\Blueprint as BlueprintFacade;
use Statamic\Fields\Blueprint;
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

function servicesBlueprint(array $overrides = []): Blueprint
{
    return BlueprintFacade::makeFromFields(array_merge([
        'title' => ['type' => 'text', 'required' => true],
        'description' => ['type' => 'textarea'],
        'duration' => ['type' => 'integer', 'default' => 42],
        'featured' => ['type' => 'toggle'],
    ], $overrides));
}
