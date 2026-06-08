<?php

namespace Clapperbrd\Codegen\Resources;

use Illuminate\Support\Str;

class ComponentTypes
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
            Str::of($this->verb)->singular()->studly()->append('Types.ts'),
        ];

        return implode('/', $segments);
    }

    public function filename(): string
    {
        return Str::of($this->verb)->singular()->studly()->append('Types.ts');
    }
}
