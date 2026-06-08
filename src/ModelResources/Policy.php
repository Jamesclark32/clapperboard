<?php

namespace Clapperbrd\Codegen\ModelResources;

use Illuminate\Support\Collection;
use Illuminate\Support\Str;

class Policy
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
            'app',
            'Policies',
            Str::of($this->resourceName)->studly()->singular()->append('Policy.php'),
        ]);

        return $segments->implode('/');
    }

    public function className(): string
    {
        return Str::of($this->resourceName)->singular()->studly()->append('Policy');
    }
}
