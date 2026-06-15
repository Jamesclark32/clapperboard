<?php

namespace JamesClark32\Clapperboard\ModelResources;

use Illuminate\Support\Collection;
use Illuminate\Support\Str;

class Model
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
        $segments = [
            'app',
            'Models',
            Str::of($this->resourceName)->singular()->studly()->append('.php'),
        ];

        return implode('/', $segments);
    }

    public function path(): string
    {
        $segments = collect([
            'app',
            'Models',
        ]);

        return $segments->implode('/');
    }

    public function filename(): string
    {
        return Str::of($this->verb)->studly()->singular()->append('.php');
    }

    public function qualifiedName(): string
    {
        $segments = collect([
            'App',
            'Models',
        ]);

        $segments->push(Str::of($this->resourceName)->studly()->singular());

        return $segments->implode('\\');
    }

    public function className(): string
    {
        return Str::of($this->resourceName)->studly()->singular();
    }

    public function namespace(): string
    {
        $segments = collect([
            'App',
            'Models',
        ]);

        return $segments->implode('\\');
    }
}
