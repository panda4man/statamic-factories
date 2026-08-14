<?php

use Panda4man\StatamicFactories\Fields\MarkdownFieldGenerator;
use Panda4man\StatamicFactories\Support\FactoryContext;

it('generates a non-empty markdown string', function () {
    $value = (new MarkdownFieldGenerator)->generate(fieldSchemaOfType('markdown'), new FactoryContext);

    expect($value)->toBeString()->not->toBeEmpty();
});

it('contains a heading marker', function () {
    $value = (new MarkdownFieldGenerator)->generate(fieldSchemaOfType('markdown'), new FactoryContext);

    expect($value)->toContain('## ');
});

it('contains a list item marker', function () {
    $value = (new MarkdownFieldGenerator)->generate(fieldSchemaOfType('markdown'), new FactoryContext);

    expect($value)->toContain('- ');
});

it('produces different output across calls, proving it is not hardcoded', function () {
    $schema = fieldSchemaOfType('markdown');

    fake()->seed(1);
    $first = (new MarkdownFieldGenerator)->generate($schema, new FactoryContext);

    fake()->seed(2);
    $second = (new MarkdownFieldGenerator)->generate($schema, new FactoryContext);

    expect($first)->not->toBe($second);
});
