<?php

namespace Clapperbrd\Codegen\ModelResources;

use Illuminate\Support\Collection;
use Illuminate\Support\Str;

class SeederConfig
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
            'config',
            'seeders',
            Str::of($this->resourceName)->snake()->plural()->append('.php'),
        ]);

        return $segments->implode('/');
    }
}
