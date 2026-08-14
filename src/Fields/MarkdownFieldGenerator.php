<?php

namespace Panda4man\StatamicFactories\Fields;

use Panda4man\StatamicFactories\Blueprints\FieldSchema;
use Panda4man\StatamicFactories\Contracts\FieldGenerator;
use Panda4man\StatamicFactories\Support\FactoryContext;

class MarkdownFieldGenerator implements FieldGenerator
{
    public function generate(FieldSchema $field, FactoryContext $context): mixed
    {
        $faker = $context->faker();

        return implode("\n\n", [
            '## '.rtrim($faker->sentence(4), '.'),
            $faker->paragraph(),
            '- '.implode("\n- ", $faker->words(3)),
            $faker->paragraph(),
        ]);
    }
}
