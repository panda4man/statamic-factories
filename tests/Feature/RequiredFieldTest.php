<?php

use Panda4man\StatamicFactories\Factories\EntryFactory;
use Panda4man\StatamicFactories\Factories\RequiredFieldMissingException;
use Statamic\Facades\Blueprint;
use Statamic\Facades\Collection;
use Statamic\Testing\Concerns\PreventsSavingStacheItemsToDisk;

uses(PreventsSavingStacheItemsToDisk::class);

beforeEach(function () {
    Collection::make('services')->save();
});

it('throws when a required field has no generator, default, or explicit value', function () {
    $blueprint = Blueprint::makeFromFields([
        'title' => ['type' => 'text', 'required' => true],
        'hero_image' => ['type' => 'assets', 'required' => true],
    ]);

    expect(fn () => EntryFactory::collection('services')->blueprint($blueprint)->make())
        ->toThrow(RequiredFieldMissingException::class);
});

it('does not throw when the missing required value is supplied explicitly', function () {
    $blueprint = Blueprint::makeFromFields([
        'title' => ['type' => 'text', 'required' => true],
        'hero_image' => ['type' => 'assets', 'required' => true],
    ]);

    $entry = EntryFactory::collection('services')->blueprint($blueprint)->make(['hero_image' => 'foo.jpg']);

    expect($entry->get('hero_image'))->toBe('foo.jpg');
});
