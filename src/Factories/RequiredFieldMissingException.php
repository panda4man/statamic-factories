<?php

namespace Panda4man\StatamicFactories\Factories;

use RuntimeException;

class RequiredFieldMissingException extends RuntimeException
{
    public function __construct(?string $blueprintHandle, array $fields)
    {
        $list = implode(', ', $fields);
        $blueprintHandle ??= '(unnamed)';

        parent::__construct(
            "The blueprint [{$blueprintHandle}] requires the following field(s), but no value could be resolved: [{$list}]. ".
            'Provide one via state(), a class-based definition(), an explicit attribute, or register a FieldGenerator for the fieldtype.'
        );
    }
}
