<?php

namespace Panda4man\StatamicFactories\Fields;

use Panda4man\StatamicFactories\Blueprints\FieldSchema;
use Panda4man\StatamicFactories\Contracts\FieldGenerator;
use Panda4man\StatamicFactories\Fields\Concerns\ResolvesSelectOptions;
use Panda4man\StatamicFactories\Support\FactoryContext;

class WidthFieldGenerator implements FieldGenerator
{
    use ResolvesSelectOptions;

    public function generate(FieldSchema $field, FactoryContext $context): mixed
    {
        $keys = $this->optionKeys($field->config['options'] ?? []);

        return $context->faker()->randomElement($keys ?: [25, 33, 50, 66, 75, 100]);
    }
}
