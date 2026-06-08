<?php

namespace Clapperbrd\Codegen\Generators;

use Clapperbrd\Codegen\ModelResource;

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
        return view('clapperbrd-codegen::templates.nova_resource', ['resource' => $this->resource])->render();
    }
}
