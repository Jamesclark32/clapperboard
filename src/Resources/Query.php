<?php

namespace JamesClark32\Clapperboard\Resources;

use Illuminate\Support\Str;

class Query
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
            'Queries',
            Str::of($this->resourceName)->plural()->studly(),
            Str::of($this->verb)->singular()->studly()->append('Query.php'),
        ];

        return implode('/', $segments);
    }

    public function path(): string
    {
        $segments = collect([
            'App',
            'Http',
            'Controllers',
        ]);

        $segments->push(Str::of($this->resourceName)->studly()->plural());

        return $segments->implode('\\');
    }

    public function filename(): string
    {
        return Str::of($this->verb)->studly()->singular()->append('Controller.php');
    }

    public function nameQualified(): string
    {
        $segments = collect([
            'App',
            'Http',
            'Queries',
        ]);

        $segments->push(Str::of($this->resourceName)->studly()->plural());
        $segments->push(Str::of($this->verb)->studly()->singular()->append('Query'));

        return $segments->implode('\\');
    }

    public function className(): string
    {
        return Str::of($this->verb)->studly()->singular()->append('Query');
    }

    public function namespace(): string
    {
        $segments = collect([
            'App',
            'Http',
            'Queries',
        ]);

        $segments->push(Str::of($this->resourceName)->studly()->plural());

        return $segments->implode('\\');
    }
}
