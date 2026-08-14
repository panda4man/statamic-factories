<?php

use Statamic\Facades\Collection;
use Statamic\Testing\Concerns\PreventsSavingStacheItemsToDisk;
use Tests\Factories\ServiceFactory;

uses(PreventsSavingStacheItemsToDisk::class);

beforeEach(function () {
    Collection::make('services')->save();
});

it('uses the collection and defaults declared on the class-based factory', function () {
    $entry = ServiceFactory::new()->create();

    expect($entry->collectionHandle())->toBe('services')
        ->and($entry->get('featured'))->toBeFalse();
});

it('supports named state methods', function () {
    $entry = ServiceFactory::new()->featured()->create();

    expect($entry->get('featured'))->toBeTrue();
});

it('lets explicit create() attributes win over class-based state, per spec example', function () {
    $entry = ServiceFactory::new()->featured()->create(['title' => 'My Test Service']);

    expect($entry->get('featured'))->toBeTrue()
        ->and($entry->get('title'))->toBe('My Test Service');
});
