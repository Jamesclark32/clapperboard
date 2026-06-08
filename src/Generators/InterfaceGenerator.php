<?php

namespace Clapperbrd\Codegen\Generators;

use Clapperbrd\Codegen\ModelResource;
use Illuminate\Support\Str;

class InterfaceGenerator
{
    private ModelResource $resource;

    public function __construct(ModelResource $resource)
    {
        $this->resource = $resource;
    }

    public function path(): string
    {
        $segments = [
            'resources',
            'js',
            'types',
            'models',
            Str::of($this->resource->resource)->singular()->snake()->append('.ts'),
        ];

        return implode('/', $segments);
    }

    public function fileContents(): string
    {
        return view('clapperbrd-codegen::templates.interface', ['resource' => $this->resource])->render();
    }
}
