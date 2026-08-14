<?php

use Panda4man\StatamicFactories\Factories\EntryFactory;
use Statamic\Facades\Collection;
use Statamic\Testing\Concerns\PreventsSavingStacheItemsToDisk;

uses(PreventsSavingStacheItemsToDisk::class);

beforeEach(function () {
    Collection::make('services')->save();
});

it('disambiguates colliding slugs within the same count() batch instead of silently overwriting entries', function () {
    $entries = EntryFactory::collection('services')->count(3)->create(['title' => 'Landscaping']);

    $slugs = $entries->map->slug();

    expect($slugs->unique())->toHaveCount(3)
        ->and($slugs->sort()->values()->all())->toBe(['landscaping', 'landscaping-2', 'landscaping-3']);
});
