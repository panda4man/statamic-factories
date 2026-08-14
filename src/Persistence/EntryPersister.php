<?php

namespace Panda4man\StatamicFactories\Persistence;

use Panda4man\StatamicFactories\Contracts\Persister;
use Statamic\Contracts\Entries\Entry;
use Statamic\Facades\Entry as EntryFacade;

class EntryPersister implements Persister
{
    public function persist(mixed $entity): Entry
    {
        EntryFacade::save($entity);

        return $entity;
    }
}
