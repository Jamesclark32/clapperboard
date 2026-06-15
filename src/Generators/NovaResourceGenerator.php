<?php

namespace JamesClark32\Clapperboard\Generators;

use JamesClark32\Clapperboard\ModelResource;

class NovaResourceGenerator
{
    private ModelResource $resource;

    public function __construct(ModelResource $resource)
    {
        $this->resource = $resource;
    }

    public function path(): string
    {
        return $this->resource->novaResource->qualifiedFilename();
    }

    public function fileContents(): string
    {
        return view('clapperboard::templates.nova_resource', ['resource' => $this->resource])->render();
    }
}
