<?php

namespace Clapperbrd\Codegen\Generators;

use Clapperbrd\Codegen\ModelResource;

class MigrationGenerator
{
    private ModelResource $resource;

    public function __construct(ModelResource $resource)
    {
        $this->resource = $resource;
    }

    public function path(): string
    {
        return $this->resource->migration->qualifiedFilename();
    }

    public function fileContents(): string
    {
        return view('clapperbrd-codegen::templates.migration', ['resource' => $this->resource])->render();
    }
}
