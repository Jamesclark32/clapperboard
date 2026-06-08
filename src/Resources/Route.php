<?php

namespace Clapperbrd\Codegen\Resources;

use Clapperbrd\Codegen\Verbs;
use Illuminate\Support\Str;

class Route
{
    public string $resourceName;

    public string $verb;

    public ?string $routeKey;

    public function __construct(string $resourceName, string $verb, ?string $routeKey = null)
    {
        $this->resourceName = $resourceName;
        $this->verb = $verb;
        $this->routeKey = $routeKey;
    }

    public function qualifiedFilename(): string
    {
        $segments = [
            'routes',
            'web',
            Str::of($this->resourceName)->plural()->lower()->append('.php'),
        ];

        return implode('/', $segments);
    }

    public function url(): string
    {
        $segments = collect();

        $segments->push(Str::of($this->resourceName)->lower()->plural());

        if (in_array($this->verb, ['show', 'edit', 'update', 'destroy'])) {
            $parameter = Str::of($this->resourceName)->singular()->camel();

            if ($this->routeKey) {
                $parameter = $parameter->append(':'.$this->routeKey);
            }

            $segments->push($parameter->wrap('{', '}'));
        }

        if (in_array($this->verb, ['create', 'edit'])) {
            $segments->push($this->verb);
        }

        return $segments->implode('/');
    }

    public function name(): string
    {
        $segments = collect();

        $segments->push(Str::of($this->resourceName)->lower()->plural());
        $segments->push(Str::of($this->verb)->lower()->singular());

        return $segments->implode('.');
    }

    public function httpVerb(): string
    {
        return Verbs::httpVerb($this->verb);
    }
}
