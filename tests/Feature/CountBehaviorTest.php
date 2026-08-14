<?php

use Illuminate\Support\Collection as SupportCollection;
use Panda4man\StatamicFactories\Factories\EntryFactory;
use Statamic\Contracts\Entries\Entry as EntryContract;
use Statamic\Facades\Collection;
use Statamic\Testing\Concerns\PreventsSavingStacheItemsToDisk;

uses(PreventsSavingStacheItemsToDisk::class);

beforeEach(function () {
    Collection::make('services')->save();
});

it('returns an empty collection and persists nothing for count(0)', function () {
    $entries = EntryFactory::collection('services')->count(0)->create();

    expect($entries)->toBeInstanceOf(SupportCollection::class)
        ->and($entries)->toHaveCount(0);
});

it('returns a collection of one when count(1) is called explicitly, for both create() and make()', function () {
    $created = EntryFactory::collection('services')->count(1)->create();
    $made = EntryFactory::collection('services')->count(1)->make();

    expect($created)->toBeInstanceOf(SupportCollection::class)->toHaveCount(1)
        ->and($made)->toBeInstanceOf(SupportCollection::class)->toHaveCount(1);
});

it('returns a bare entry, not a collection, when count() was never called', function () {
    $entry = EntryFactory::collection('services')->create();

    expect($entry)->toBeInstanceOf(EntryContract::class)
        ->and($entry)->not->toBeInstanceOf(SupportCollection::class);
});
