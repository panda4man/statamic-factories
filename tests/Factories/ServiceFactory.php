<?php

namespace Tests\Factories;

use Panda4man\StatamicFactories\Factories\EntryFactory;

class ServiceFactory extends EntryFactory
{
    protected ?string $collection = 'services';

    public function definition(): array
    {
        return ['featured' => false];
    }

    public function featured(): static
    {
        return $this->state(['featured' => true]);
    }
}
