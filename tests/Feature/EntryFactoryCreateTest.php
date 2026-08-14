<?php

use Panda4man\StatamicFactories\Factories\EntryFactory;
use Statamic\Facades\Collection;
use Statamic\Facades\Entry;
use Statamic\Testing\Concerns\PreventsSavingStacheItemsToDisk;

uses(PreventsSavingStacheItemsToDisk::class);

beforeEach(function () {
    Collection::make('services')->save();
});

it('persists via create() and auto-resolves the blueprint from the real collection', function () {
    $entry = EntryFactory::collection('services')->create();

    expect($entry->id())->not->toBeNull()
        ->and(Entry::find($entry->id())->id())->toBe($entry->id())
        ->and($entry->get('title'))->toBeString()->not->toBeEmpty()
        ->and($entry->get('duration'))->toBe(42);
});

it('returns a distinct, independently persisted entry for each item in count()', function () {
    $entries = EntryFactory::collection('services')->count(10)->create();

    expect($entries)->toHaveCount(10);

    $ids = $entries->map->id();

    expect($ids->unique())->toHaveCount(10)
        ->and($ids->every(fn ($id) => Entry::find($id)?->id() === $id))->toBeTrue();
});
