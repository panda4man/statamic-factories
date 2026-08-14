<?php

use Panda4man\StatamicFactories\Factories\EntryFactory;
use Statamic\Contracts\Entries\Entry as EntryContract;
use Statamic\Facades\Collection;
use Statamic\Testing\Concerns\PreventsSavingStacheItemsToDisk;

uses(PreventsSavingStacheItemsToDisk::class);

beforeEach(function () {
    // Entry::collection() resolves the real Collection object internally
    // (for entryClass() etc), so it must exist even though make() itself
    // never persists the generated entry.
    Collection::make('services')->save();
});

it('generates valid in-memory data for every blueprint field without persisting', function () {
    fake()->seed(1234);

    $entry = EntryFactory::collection('services')->blueprint(servicesBlueprint())->make();

    expect($entry)->toBeInstanceOf(EntryContract::class)
        ->and($entry->get('title'))->toBeString()->not->toBeEmpty()
        ->and($entry->get('description'))->toBeString()->not->toBeEmpty()
        // the field has a registered generator, so it overrides the blueprint default (see EntryFactoryDefaultPrecedenceTest)
        ->and($entry->get('duration'))->toBeInt()->toBeGreaterThanOrEqual(1)->toBeLessThanOrEqual(1000)
        ->and($entry->get('featured'))->toBeBool();
});

it('never persists during make()', function () {
    $entry = EntryFactory::collection('services')->blueprint(servicesBlueprint())->make();

    // make() must never assign an id or hit the Stache — both are side
    // effects reserved for create()/persistence.
    expect($entry->id())->toBeNull();
});
