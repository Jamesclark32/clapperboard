<?php

namespace Clapperbrd\Codegen\Resources;

use Illuminate\Support\Str;

class Controller
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
            'Controllers',
            Str::of($this->resourceName)->plural()->studly(),
            Str::of($this->verb)->singular()->studly()->append('Controller.php'),
        ];

        return implode('/', $segments);
    }

    public function path(): string
    {
        $segments = collect([
            'Http',
            'Controllers',
        ]);

        $segments->push(Str::of($this->resourceName)->studly()->plural());

        return $segments->implode('/');
    }

    public function filename(): string
    {
        return Str::of($this->verb)->studly()->singular()->append('Controller.php');
    }

    public function qualifiedName(): string
    {
        $segments = collect([
            'App',
            'Http',
            'Controllers',
        ]);

        $segments->push(Str::of($this->resourceName)->studly()->plural());
        $segments->push(Str::of($this->verb)->studly()->singular()->append('Controller'));

        return $segments->implode('\\');
    }

    public function qualifiedBaseController(): string
    {
        $segments = collect([
            'App',
            'Http',
            'Controllers',
            'Controller',
        ]);

        return $segments->implode('\\');
    }

    public function className(): string
    {
        return Str::of($this->verb)->studly()->singular()->append('Controller');
    }

    public function namespace(): string
    {
        $segments = collect([
            'App',
            'Http',
            'Controllers',
        ]);

        $segments->push(Str::of($this->resourceName)->studly()->plural());

        return $segments->implode('\\');
    }

    public function policyParameter(): string
    {
        if (in_array($this->verb, ['index', 'create'])) {
            return Str::of($this->resourceName)->studly()->singular()->append('::class');
        }

        return Str::of($this->resourceName)->camel()->singular()->prepend('$');

    }
}
