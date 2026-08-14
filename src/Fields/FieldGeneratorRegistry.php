<?php

namespace Panda4man\StatamicFactories\Fields;

use Illuminate\Contracts\Container\Container;

class FieldGeneratorRegistry
{
    public function __construct(protected Container $container)
    {
    }
}
