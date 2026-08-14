<?php

namespace Panda4man\StatamicFactories\Support;

use Faker\Generator;

class FactoryContext
{
    public function __construct(
        protected ?string $collectionHandle = null,
        protected ?string $blueprintHandle = null,
        protected ?string $site = null,
        protected int $index = 0,
    ) {
    }

    public function faker(): Generator
    {
        return fake();
    }

    public function collectionHandle(): ?string
    {
        return $this->collectionHandle;
    }

    public function blueprintHandle(): ?string
    {
        return $this->blueprintHandle;
    }

    public function site(): ?string
    {
        return $this->site;
    }

    public function index(): int
    {
        return $this->index;
    }
}
