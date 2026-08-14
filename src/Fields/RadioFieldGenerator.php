<?php

namespace Panda4man\StatamicFactories\Fields;

use Panda4man\StatamicFactories\Blueprints\FieldSchema;
use Panda4man\StatamicFactories\Contracts\FieldGenerator;
use Panda4man\StatamicFactories\Fields\Concerns\ResolvesSelectOptions;
use Panda4man\StatamicFactories\Support\FactoryContext;

class RadioFieldGenerator implements FieldGenerator
{
    use ResolvesSelectOptions;

    public function generate(FieldSchema $field, FactoryContext $context): mixed
    {
        $keys = $this->optionKeys($field->config['options'] ?? []);

        if (empty($keys)) {
            return null;
        }

        return $context->faker()->randomElement($keys);
    }
}
