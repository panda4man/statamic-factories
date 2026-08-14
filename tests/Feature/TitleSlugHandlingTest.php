<?php

use Panda4man\StatamicFactories\Factories\EntryFactory;
use Statamic\Facades\Collection;
use Statamic\Testing\Concerns\PreventsSavingStacheItemsToDisk;

uses(PreventsSavingStacheItemsToDisk::class);

beforeEach(function () {
    Collection::make('services')->save();
});

it('derives the slug from the title by default', function () {
    $entry = EntryFactory::collection('services')->create(['title' => 'My Example Service']);

    expect($entry->slug())->toBe('my-example-service');
});

it('lets an explicit slug win over the derived one', function () {
    $entry = EntryFactory::collection('services')->create([
        'title' => 'My Example Service',
        'slug' => 'custom-slug',
    ]);

    expect($entry->slug())->toBe('custom-slug');
});
