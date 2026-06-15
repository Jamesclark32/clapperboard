<?php

namespace JamesClark32\Clapperboard\Resources;

use Illuminate\Support\Str;
use JamesClark32\Clapperboard\Verbs;

class Action
{
    public string $resourceName;

    public string $verb;

    public function __construct(string $resourceName, string $verb)
    {
        $this->resourceName = $resourceName;
        $this->verb = $verb;
    }

    public function qualifiedClassname(): string
    {
        $segments = collect([
            'App',
            'Http',
            $this->namespaceSegment(),
        ]);

        $segments->push(Str::of($this->resourceName)->studly()->plural());
        $segments->push($this->className());

        return $segments->implode('\\');
    }

    public function qualifiedFilename(): string
    {
        $segments = [
            'app',
            'Http',
            $this->namespaceSegment(),
            Str::of($this->resourceName)->studly()->plural(),
            Str::of($this->className())->append('.php'),
        ];

        return implode('/', $segments);
    }

    public function variableName(): string
    {
        if (Verbs::isQuery($this->verb)) {
            return '$query';
        }

        return '$command';
    }

    public function entryMethod(): string
    {
        if (Verbs::isQuery($this->verb)) {
            return 'getProps';
        }

        return 'process';
    }

    public function className(): string
    {
        return Str::of($this->verb)->studly()->singular()->append($this->qualifier())->toString();
    }

    public function namespace(): string
    {
        return collect([
            'App',
            'Http',
            $this->namespaceSegment(),
            Str::of($this->resourceName)->studly()->plural(),
        ])->implode('\\');
    }

    private function qualifier(): string
    {
        return Verbs::isQuery($this->verb) ? 'Query' : 'Command';
    }

    private function namespaceSegment(): string
    {
        return Verbs::isQuery($this->verb) ? 'Queries' : 'Commands';
    }
}
