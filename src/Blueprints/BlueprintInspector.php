<?php

namespace Panda4man\StatamicFactories\Blueprints;

use Statamic\Fields\Blueprint;

class BlueprintInspector
{
    public function inspect(Blueprint $blueprint): BlueprintSchema
    {
        $fields = $blueprint->fields()->all()
            ->mapWithKeys(fn ($field) => [$field->handle() => FieldSchema::fromField($field)]);

        return new BlueprintSchema($fields);
    }
}
