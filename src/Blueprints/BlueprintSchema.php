<?php

namespace Panda4man\StatamicFactories\Blueprints;

use Illuminate\Support\Collection;

class BlueprintSchema
{
    /**
     * @param  Collection<string, FieldSchema>  $fields
     */
    public function __construct(protected Collection $fields)
    {
    }

    public function fields(): Collection
    {
        return $this->fields;
    }
}
