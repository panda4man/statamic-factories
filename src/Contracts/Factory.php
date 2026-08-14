<?php

namespace Panda4man\StatamicFactories\Contracts;

use Closure;

interface Factory
{
    public function count(int $count): static;

    public function state(array|Closure $state): static;

    public function make(array $attributes = []): mixed;

    public function create(array $attributes = []): mixed;
}
