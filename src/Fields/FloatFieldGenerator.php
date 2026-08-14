<?php

namespace Panda4man\StatamicFactories\Fields;

use Panda4man\StatamicFactories\Blueprints\FieldSchema;
use Panda4man\StatamicFactories\Contracts\FieldGenerator;
use Panda4man\StatamicFactories\Support\FactoryContext;

class FloatFieldGenerator implements FieldGenerator
{
    public function generate(FieldSchema $field, FactoryContext $context): mixed
    {
        return $context->faker()->randomFloat(
            2,
            $field->config['min'] ?? 1,
            $field->config['max'] ?? 1000,
        );
    }
}
