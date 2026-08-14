<?php

use Panda4man\StatamicFactories\Factories\EntryFactory;
use Statamic\Facades\Collection;
use Statamic\Testing\Concerns\PreventsSavingStacheItemsToDisk;

uses(PreventsSavingStacheItemsToDisk::class);

beforeEach(function () {
    Collection::make('services')->save();
});

it('state() always wins over the generated value, regardless of the Faker seed', function () {
    $entries = EntryFactory::collection('services')
        ->state(['featured' => true])
        ->count(10)
        ->create();

    expect($entries->every(fn ($entry) => $entry->get('featured') === true))->toBeTrue();
});

it('lets a state closure derive its value from an explicit attribute passed to create()', function () {
    $entry = EntryFactory::collection('services')
        ->state(fn (array $attrs) => ['description' => 'Title is: '.$attrs['title']])
        ->create(['title' => 'Landscaping']);

    expect($entry->get('description'))->toBe('Title is: Landscaping')
        ->and($entry->get('title'))->toBe('Landscaping');
});
