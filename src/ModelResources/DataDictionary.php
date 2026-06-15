<?php

namespace JamesClark32\Clapperboard\ModelResources;

use Illuminate\Support\Collection;
use Illuminate\Support\Str;

class DataDictionary
{
    public string $resourceName;

    public Collection $attributes;

    public function __construct(string $resourceName, Collection $attributes)
    {
        $this->resourceName = $resourceName;
        $this->attributes = $attributes;
    }

    public function qualifiedFilename(): string
    {
        $segments = collect([
            'docs',
            'DataDictionary',
            Str::of($this->resourceName)->studly()->singular()->append('.md'),
        ]);

        return $segments->implode('/');
    }
}
