<?php

namespace Panda4man\StatamicFactories\Contracts;

interface Persister
{
    public function persist(mixed $entity): mixed;
}
