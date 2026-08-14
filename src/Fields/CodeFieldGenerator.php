<?php

namespace Panda4man\StatamicFactories\Fields;

use Panda4man\StatamicFactories\Blueprints\FieldSchema;
use Panda4man\StatamicFactories\Contracts\FieldGenerator;
use Panda4man\StatamicFactories\Support\FactoryContext;

class CodeFieldGenerator implements FieldGenerator
{
    public function generate(FieldSchema $field, FactoryContext $context): mixed
    {
        $mode = $field->config['mode'] ?? 'htmlmixed';
        $code = sprintf('%s(%d);', $context->faker()->word(), $context->faker()->numberBetween(1, 100));

        return ($field->config['mode_selectable'] ?? true)
            ? ['code' => $code, 'mode' => $mode]
            : $code;
    }
}
