<?php

namespace Panda4man\StatamicFactories\Fields\Concerns;

trait ResolvesSelectOptions
{
    /**
     * @param  array<int|string, mixed>  $options
     * @return array<int, int|string>
     */
    protected function optionKeys(array $options): array
    {
        if (empty($options)) {
            return [];
        }

        if (array_is_list($options) && is_array($options[0] ?? null)) {
            return array_column($options, 'key');
        }

        if (array_is_list($options)) {
            return $options;
        }

        return array_keys($options);
    }
}
