<?php

use Panda4man\StatamicFactories\Factories\EntryFactory;
use Statamic\Facades\Collection;
use Statamic\Testing\Concerns\PreventsSavingStacheItemsToDisk;

uses(PreventsSavingStacheItemsToDisk::class);

beforeEach(function () {
    Collection::make('services')->save();
});

it('lets an explicit create() attribute win over a state() value', function () {
    $entry = EntryFactory::collection('services')
        ->state(['title' => 'From State'])
        ->create(['title' => 'Explicit Title']);

    expect($entry->get('title'))->toBe('Explicit Title');
});
