<?php

namespace Panda4man\StatamicFactories\Fields;

use Panda4man\StatamicFactories\Blueprints\FieldSchema;
use Panda4man\StatamicFactories\Contracts\FieldGenerator;
use Panda4man\StatamicFactories\Support\FactoryContext;

class YamlFieldGenerator implements FieldGenerator
{
    public function generate(FieldSchema $field, FactoryContext $context): mixed
    {
        return [
            'title' => $context->faker()->sentence(3),
            'enabled' => $context->faker()->boolean(),
            'count' => $context->faker()->numberBetween(1, 100),
        ];
    }
}
