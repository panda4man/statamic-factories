<?php

namespace Panda4man\StatamicFactories\Blueprints;

use Statamic\Fields\Field;

class FieldSchema
{
    public function __construct(
        public readonly string $handle,
        public readonly string $type,
        public readonly bool $required,
        public readonly mixed $default,
        public readonly array $rules,
        public readonly array $config,
    ) {
    }

    public static function fromField(Field $field): self
    {
        return new self(
            handle: $field->handle(),
            type: $field->type(),
            required: $field->isRequired(),
            default: $field->config()['default'] ?? null,
            rules: $field->rules(),
            config: $field->config(),
        );
    }
}
