<?php

namespace Tests;

use Panda4man\StatamicFactories\StatamicFactoriesServiceProvider;
use Statamic\Testing\AddonTestCase;

abstract class TestCase extends AddonTestCase
{
    protected string $addonServiceProvider = StatamicFactoriesServiceProvider::class;

    protected function getEnvironmentSetUp($app)
    {
        parent::getEnvironmentSetUp($app);

        $app['config']->set(
            'statamic.system.blueprints_path',
            __DIR__.'/__fixtures__/blueprints'
        );
    }
}
