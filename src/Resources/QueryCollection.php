<?php

namespace Clapperbrd\Codegen\Resources;

use Illuminate\Support\Str;

class QueryCollection
{
    public string $resourceName;

    public string $verb;

    public function __construct(string $resourceName, string $verb)
    {
        $this->resourceName = $resourceName;
        $this->verb = $verb;
    }

    public function qualifiedFilename(): string
    {
        $segments = [
            'app',
            'Http',
            'Collections',
            Str::of($this->resourceName)->studly()->plural(),
            $this->className().'.php',
        ];

        return implode('/', $segments);
    }

    public function className(): string
    {
        return Str::of($this->resourceName)->studly()->plural()
            .Str::of($this->verb)->studly()->singular()
            .'Collection';
    }

    public function namespace(): string
    {
        return collect([
            'App',
            'Http',
            'Collections',
            Str::of($this->resourceName)->studly()->plural(),
        ])->implode('\\');
    }

    public function qualifiedClassName(): string
    {
        return $this->namespace().'\\'.$this->className();
    }

    /**
     * Get the Wayfinder function name for this collection.
     */
    public function wayfinderFunction(): string
    {
        return Str::of($this->resourceName)->camel()->plural()
            .Str::of($this->verb)->studly()->singular();
    }
}
