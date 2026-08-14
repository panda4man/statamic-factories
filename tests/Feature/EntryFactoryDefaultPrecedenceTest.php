<?php

use Panda4man\StatamicFactories\Factories\EntryFactory;
use Statamic\Facades\Blueprint;
use Statamic\Facades\Collection;
use Statamic\Testing\Concerns\PreventsSavingStacheItemsToDisk;

uses(PreventsSavingStacheItemsToDisk::class);

beforeEach(function () {
    Collection::make('services')->save();
});

it('lets a registered generator override the blueprint default, per the documented precedence', function () {
    $blueprint = Blueprint::makeFromFields([
        'title' => ['type' => 'text', 'required' => true],
        'duration' => ['type' => 'integer', 'default' => 42],
    ]);

    $sawNonDefault = false;

    foreach (range(1, 30) as $seed) {
        fake()->seed($seed);
        $entry = EntryFactory::collection('services')->blueprint($blueprint)->make();

        if ($entry->get('duration') !== 42) {
            $sawNonDefault = true;
            break;
        }
    }

    expect($sawNonDefault)->toBeTrue();
});

it('falls back to the blueprint default when no generator is registered for the fieldtype', function () {
    $blueprint = Blueprint::makeFromFields([
        'title' => ['type' => 'text', 'required' => true],
        'hero_image' => ['type' => 'assets', 'default' => 'placeholder.jpg'],
    ]);

    $entry = EntryFactory::collection('services')->blueprint($blueprint)->make();

    expect($entry->get('hero_image'))->toBe('placeholder.jpg');
});
