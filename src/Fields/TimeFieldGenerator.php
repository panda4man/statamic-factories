<?php

namespace Panda4man\StatamicFactories\Fields;

use Panda4man\StatamicFactories\Blueprints\FieldSchema;
use Panda4man\StatamicFactories\Contracts\FieldGenerator;
use Panda4man\StatamicFactories\Support\FactoryContext;

class TimeFieldGenerator implements FieldGenerator
{
    public function generate(FieldSchema $field, FactoryContext $context): mixed
    {
        return $context->faker()->time(($field->config['seconds_enabled'] ?? false) ? 'H:i:s' : 'H:i');
    }
}
