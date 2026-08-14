<?php

return [
    'field_generators' => [
        'text' => \Panda4man\StatamicFactories\Fields\TextFieldGenerator::class,
        'textarea' => \Panda4man\StatamicFactories\Fields\TextareaFieldGenerator::class,
        'integer' => \Panda4man\StatamicFactories\Fields\IntegerFieldGenerator::class,
        'toggle' => \Panda4man\StatamicFactories\Fields\ToggleFieldGenerator::class,
    ],

    // skip | null | throw
    'unsupported_fieldtype_behavior' => 'null',

    'default_published' => true,
];
