<?php

namespace JamesClark32\Clapperboard\Resources;

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
            Str::of($this->resourceName)->plural()->snake(),
            Str::of($this->verb)->singular()->snake()->append('.php'),
        ]);
    }

    public function key(?string $path = null): string
    {
        $key = collect([
            Str::of($this->resourceName)->plural()->snake(),
            Str::of($this->verb)->singular()->snake(),
        ])->implode('/');

        if ($path) {
            $key .= '.'.$path;
        }

        return $key;
    }
}
