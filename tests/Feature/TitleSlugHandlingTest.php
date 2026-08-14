<?php

use Panda4man\StatamicFactories\Factories\EntryFactory;
use Statamic\Facades\Blueprint;
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

it('falls back to the derived slug when an explicit slug is an empty string', function () {
    $entry = EntryFactory::collection('services')->create([
        'title' => 'My Example Service',
        'slug' => '',
    ]);

    expect($entry->slug())->toBe('my-example-service');
});

it('does not silently fall back to the title-derived slug for a legitimately falsy explicit slug like "0"', function () {
    // Statamic's own Routable::slug() getter treats any falsy stored value
    // (including '0') as unset and returns null — an upstream constraint we
    // can't work around. What we can and must guarantee at this layer is
    // that '0' is honored as the explicit override and never silently
    // replaced by the title-derived slug ('my-example-service').
    $entry = EntryFactory::collection('services')->create([
        'title' => 'My Example Service',
        'slug' => '0',
    ]);

    expect($entry->slug())->not->toBe('my-example-service');
});

it('synthesizes a title (and derives a slug from it) when the blueprint does not declare a title field', function () {
    $blueprint = Blueprint::makeFromFields([
        'description' => ['type' => 'textarea'],
    ]);

    $entry = EntryFactory::collection('services')->blueprint($blueprint)->make();

    expect($entry->get('title'))->toBeString()->not->toBeEmpty()
        ->and($entry->slug())->not->toBeNull()->not->toBeEmpty();
});
