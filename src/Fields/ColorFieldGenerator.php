<?php

namespace Panda4man\StatamicFactories\Fields;

use Panda4man\StatamicFactories\Blueprints\FieldSchema;
use Panda4man\StatamicFactories\Contracts\FieldGenerator;
use Panda4man\StatamicFactories\Support\FactoryContext;

class ColorFieldGenerator implements FieldGenerator
{
    public function generate(FieldSchema $field, FactoryContext $context): mixed
    {
        $swatches = $field->config['swatches'] ?? [];

        if (! empty($swatches)) {
            return $context->faker()->randomElement($swatches);
        }

        return $context->faker()->hexColor();
    }
}
