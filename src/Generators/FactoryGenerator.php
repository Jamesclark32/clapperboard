<?php

namespace JamesClark32\Clapperboard\Generators;

use JamesClark32\Clapperboard\ModelResource;

class FactoryGenerator
{
    private ModelResource $resource;

    public function __construct(ModelResource $resource)
    {
        $this->resource = $resource;
    }

    public function path(): string
    {
        return $this->resource->factory->qualifiedFilename();
    }

    public function fileContents(): string
    {
        return view('clapperboard::templates.factory', ['resource' => $this->resource])->render();
    }
}
