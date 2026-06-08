<?php

namespace Clapperbrd\Codegen\Generators;

use Clapperbrd\Codegen\ModelResource;

class DataDictionaryGenerator
{
    private ModelResource $resource;

    public function __construct(ModelResource $resource)
    {
        $this->resource = $resource;
    }

    public function path(): string
    {
        return $this->resource->dataDictionary->qualifiedFilename();
    }

    public function fileContents(): string
    {
        return view('clapperbrd-codegen::templates.data_dictionary', ['resource' => $this->resource])->render();
    }
}
