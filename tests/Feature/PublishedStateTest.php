<?php

use Panda4man\StatamicFactories\Factories\EntryFactory;
use Statamic\Facades\Collection;
use Statamic\Testing\Concerns\PreventsSavingStacheItemsToDisk;

uses(PreventsSavingStacheItemsToDisk::class);

beforeEach(function () {
    Collection::make('services')->save();
});

it('applies the configured default_published value to created entries', function () {
    config(['statamic-factories.default_published' => false]);

    $entry = EntryFactory::collection('services')->create();

    expect($entry->published())->toBeFalse();
});

it('lets an explicit published attribute override the config default', function () {
    config(['statamic-factories.default_published' => false]);

    $entry = EntryFactory::collection('services')->create(['published' => true]);

    expect($entry->published())->toBeTrue();
});

it('publishes by default when default_published is left at its default of true', function () {
    $entry = EntryFactory::collection('services')->create();

    expect($entry->published())->toBeTrue();
});
