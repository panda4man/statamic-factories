<?php

namespace Panda4man\StatamicFactories\Fields;

use Panda4man\StatamicFactories\Blueprints\FieldSchema;
use Panda4man\StatamicFactories\Contracts\FieldGenerator;
use Panda4man\StatamicFactories\Fields\Concerns\ResolvesSelectOptions;
use Panda4man\StatamicFactories\Support\FactoryContext;

class SelectFieldGenerator implements FieldGenerator
{
    use ResolvesSelectOptions;

    public function generate(FieldSchema $field, FactoryContext $context): mixed
    {
        $keys = $this->optionKeys($field->config['options'] ?? []);

        if (empty($keys)) {
            return null;
        }

        if ($field->config['multiple'] ?? false) {
            $max = min(count($keys), $field->config['max_items'] ?? count($keys));
            $n = $context->faker()->numberBetween(1, max(1, $max));

            return $context->faker()->randomElements($keys, $n);
        }

        return $context->faker()->randomElement($keys);
    }
}
