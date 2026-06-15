<?php

namespace JamesClark32\Clapperboard\Resources;

use Illuminate\Support\Str;

class Component
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
            'resources',
            'js',
            'pages',
            Str::of($this->resourceName)->plural()->studly(),
            Str::of($this->verb)->singular()->studly()->append('.vue'),
        ];

        return implode('/', $segments);
    }

    public function qualifiedFormFilename(): string
    {
        $segments = [
            'resources',
            'js',
            'pages',
            Str::of($this->resourceName)->plural()->studly(),
            'Form.vue',
        ];

        return implode('/', $segments);
    }

    public function namespacedName(): string
    {
        $segments = collect();

        $segments->push(Str::of($this->resourceName)->studly()->plural());
        $segments->push(Str::of($this->verb)->studly()->singular());

        return $segments->implode('/');
    }

    public function pageTitle(): string
    {
        if ($this->verb === 'index') {
            return Str::of($this->resourceName)->title()->plural()->toString();
        }

        if ($this->verb === 'show') {
            return Str::of($this->resourceName)->title()->singular()->toString();
        }

        $segments = collect();

        $segments->push(Str::of($this->verb)->title()->singular());
        $segments->push(Str::of($this->resourceName)->title()->singular());

        return $segments->implode(' ');
    }
}
