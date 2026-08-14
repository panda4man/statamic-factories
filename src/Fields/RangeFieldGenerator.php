<?php

namespace Panda4man\StatamicFactories\Fields;

use Panda4man\StatamicFactories\Blueprints\FieldSchema;
use Panda4man\StatamicFactories\Contracts\FieldGenerator;
use Panda4man\StatamicFactories\Support\FactoryContext;

class RangeFieldGenerator implements FieldGenerator
{
    public function generate(FieldSchema $field, FactoryContext $context): mixed
    {
        $min = (float) ($field->config['min'] ?? 0);
        $max = (float) ($field->config['max'] ?? 100);
        $step = (float) ($field->config['step'] ?? 1) ?: 1.0;
        $max = max($min, $max);
        $steps = (int) floor(($max - $min) / abs($step));
        $value = $min + $context->faker()->numberBetween(0, max($steps, 0)) * abs($step);

        $usesDecimals = ! $this->isWholeNumber($min) || ! $this->isWholeNumber($max) || ! $this->isWholeNumber($step);

        return $usesDecimals ? round($value, 6) : (int) $value;
    }

    private function isWholeNumber(float $n): bool
    {
        return $n == (int) $n;
    }
}
