<?php

namespace Panda4man\StatamicFactories;

use Panda4man\StatamicFactories\Fields\FieldGeneratorRegistry;
use Statamic\Providers\AddonServiceProvider;

class StatamicFactoriesServiceProvider extends AddonServiceProvider
{
    public function register()
    {
        parent::register();

        $this->app->singleton(FieldGeneratorRegistry::class);
    }

    public function bootAddon()
    {
        $registry = $this->app->make(FieldGeneratorRegistry::class);

        foreach (config('statamic-factories.field_generators', []) as $type => $generator) {
            $registry->register($type, $generator);
        }
    }
}
