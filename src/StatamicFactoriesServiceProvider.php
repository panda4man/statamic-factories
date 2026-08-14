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
        //
    }
}
