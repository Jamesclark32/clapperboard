<?php

namespace JamesClark32\Clapperboard\Resources;

use Illuminate\Support\Str;

class FormRequest
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
            'Requests',
            Str::of($this->resourceName)->plural()->studly(),
            Str::of($this->verb)->singular()->studly()->append('Request.php'),
        ];

        return implode('/', $segments);
    }

    public function qualifiedClassname(): string
    {
        return collect([
            'App',
            'Http',
            'Requests',
            Str::of($this->resourceName)->plural()->studly(),
            $this->className(),
        ])->implode('\\');
    }

    public function className(): string
    {
        return Str::of($this->verb)->singular()->studly()->append('Request')->toString();
    }

    public function namespace(): string
    {
        return collect([
            'App',
            'Http',
            'Requests',
            Str::of($this->resourceName)->plural()->studly(),
        ])->implode('\\');
    }
}
