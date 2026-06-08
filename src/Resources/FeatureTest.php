<?php

namespace Clapperbrd\Codegen\Resources;

use Illuminate\Support\Str;

class FeatureTest
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
            'tests',
            'Feature',
            Str::of($this->resourceName)->plural()->studly(),
            Str::of($this->verb)->singular()->studly()->append('ControllerTest.php'),
        ];

        return implode('/', $segments);
    }
}
