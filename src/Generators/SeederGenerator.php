<?php

namespace Clapperbrd\Codegen\Generators;

use Clapperbrd\Codegen\ModelResource;

class SeederGenerator
{
    private ModelResource $resource;

    public function __construct(ModelResource $resource)
    {
        $this->resource = $resource;
    }

    public function path(): string
    {
        return $this->resource->seeder->qualifiedFilename();
    }

    public function fileContents(): string
    {
        return view('clapperbrd-codegen::templates.seeder', ['resource' => $this->resource])->render();
    }
}
