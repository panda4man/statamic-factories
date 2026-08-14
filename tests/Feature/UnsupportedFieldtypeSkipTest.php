<?php

use Panda4man\StatamicFactories\Factories\EntryFactory;
use Statamic\Facades\Blueprint;
use Statamic\Facades\Collection;
use Statamic\Testing\Concerns\PreventsSavingStacheItemsToDisk;

uses(PreventsSavingStacheItemsToDisk::class);

beforeEach(function () {
    Collection::make('services')->save();
});

it('omits an unsupported fieldtype entirely from the generated data when behavior is skip', function () {
    config(['statamic-factories.unsupported_fieldtype_behavior' => 'skip']);

    $blueprint = Blueprint::makeFromFields([
        'title' => ['type' => 'text', 'required' => true],
        'hero_image' => ['type' => 'assets'],
    ]);

    $entry = EntryFactory::collection('services')->blueprint($blueprint)->make();

    expect($entry->data()->has('hero_image'))->toBeFalse();
});

it('still applies a configured default under skip behavior even without a generator', function () {
    config(['statamic-factories.unsupported_fieldtype_behavior' => 'skip']);

    $blueprint = Blueprint::makeFromFields([
        'title' => ['type' => 'text', 'required' => true],
        'hero_image' => ['type' => 'assets', 'default' => 'placeholder.jpg'],
    ]);

    $entry = EntryFactory::collection('services')->blueprint($blueprint)->make();

    expect($entry->get('hero_image'))->toBe('placeholder.jpg');
});
