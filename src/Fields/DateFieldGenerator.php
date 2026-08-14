<?php

namespace Panda4man\StatamicFactories\Fields;

use Panda4man\StatamicFactories\Blueprints\FieldSchema;
use Panda4man\StatamicFactories\Contracts\FieldGenerator;
use Panda4man\StatamicFactories\Support\FactoryContext;

class DateFieldGenerator implements FieldGenerator
{
    public function generate(FieldSchema $field, FactoryContext $context): mixed
    {
        $format = $field->config['format']
            ?? (($field->config['time_seconds_enabled'] ?? false) ? 'Y-m-d H:i:s' : 'Y-m-d H:i');

        $earliest = $field->config['earliest_date'] ?? '-1 year';
        $latest = $field->config['latest_date'] ?? 'now';

        if (($field->config['mode'] ?? null) === 'range') {
            $start = $context->faker()->dateTimeBetween($earliest, $latest);
            $end = $context->faker()->dateTimeBetween($start, $latest);

            return [
                'start' => $start->format($format),
                'end' => $end->format($format),
            ];
        }

        return $context->faker()->dateTimeBetween($earliest, $latest)->format($format);
    }
}
