<?php

namespace Panda4man\StatamicFactories\Fields;

use RuntimeException;

class UnsupportedFieldtypeException extends RuntimeException
{
    public function __construct(string $type)
    {
        parent::__construct("No field generator registered for fieldtype [{$type}].");
    }
}
