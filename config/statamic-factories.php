<?php

use Panda4man\StatamicFactories\Fields\CheckboxesFieldGenerator;
use Panda4man\StatamicFactories\Fields\ColorFieldGenerator;
use Panda4man\StatamicFactories\Fields\DateFieldGenerator;
use Panda4man\StatamicFactories\Fields\FloatFieldGenerator;
use Panda4man\StatamicFactories\Fields\IntegerFieldGenerator;
use Panda4man\StatamicFactories\Fields\RadioFieldGenerator;
use Panda4man\StatamicFactories\Fields\RangeFieldGenerator;
use Panda4man\StatamicFactories\Fields\SelectFieldGenerator;
use Panda4man\StatamicFactories\Fields\TextareaFieldGenerator;
use Panda4man\StatamicFactories\Fields\TextFieldGenerator;
use Panda4man\StatamicFactories\Fields\TimeFieldGenerator;
use Panda4man\StatamicFactories\Fields\ToggleFieldGenerator;

return [
    'field_generators' => [
        'text' => TextFieldGenerator::class,
        'textarea' => TextareaFieldGenerator::class,
        'integer' => IntegerFieldGenerator::class,
        'toggle' => ToggleFieldGenerator::class,
        'select' => SelectFieldGenerator::class,
        'radio' => RadioFieldGenerator::class,
        'checkboxes' => CheckboxesFieldGenerator::class,
        'float' => FloatFieldGenerator::class,
        'range' => RangeFieldGenerator::class,
        'time' => TimeFieldGenerator::class,
        'date' => DateFieldGenerator::class,
        'color' => ColorFieldGenerator::class,
    ],

    // skip | null | throw
    'unsupported_fieldtype_behavior' => 'null',

    'default_published' => true,
];
