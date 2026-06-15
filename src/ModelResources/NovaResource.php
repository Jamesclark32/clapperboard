<?php

namespace JamesClark32\Clapperboard\ModelResources;

use Illuminate\Support\Str;

class NovaResource
{
    public string $modelName;

    public function __construct(string $modelName)
    {
        $this->modelName = $modelName;
    }

    public function qualifiedFilename(): string
    {
        return implode('/', [
            'app',
            'Nova',
            $this->className().'.php',
        ]);
    }

    public function className(): string
    {
        return Str::of($this->modelName)->studly()->singular();
    }

    public function namespace(): string
    {
        return 'App\\Nova';
    }

    public function qualifiedClassName(): string
    {
        return $this->namespace().'\\'.$this->className();
    }

    public function modelClassName(): string
    {
        return Str::of($this->modelName)->studly()->singular();
    }
}
