<?php

namespace Panda4man\StatamicFactories\Contracts;

use Panda4man\StatamicFactories\Blueprints\FieldSchema;
use Panda4man\StatamicFactories\Support\FactoryContext;

interface FieldGenerator
{
    public function generate(FieldSchema $field, FactoryContext $context): mixed;
}
