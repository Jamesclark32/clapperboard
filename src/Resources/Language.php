<?php

namespace Clapperbrd\Codegen\Resources;

use Illuminate\Support\Str;

class Language
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
        return implode('/', [
            'lang',
            'en',
            Str::of($this->resourceName)->plural()->studly(),
            Str::of($this->verb)->singular()->studly()->append('.php'),
        ]);
    }

    public function key(?string $path = null): string
    {
        $key = collect([
            Str::of($this->resourceName)->plural()->studly(),
            Str::of($this->verb)->singular()->studly(),
        ])->implode('/');

        if ($path) {
            $key .= '.'.$path;
        }

        return $key;
    }
}
