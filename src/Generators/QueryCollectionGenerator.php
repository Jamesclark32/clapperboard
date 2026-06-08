<?php

namespace Clapperbrd\Codegen\Generators;

use Clapperbrd\Codegen\Resource;

class QueryCollectionGenerator
{
    private Resource $resource;

    public function __construct(Resource $resource)
    {
        $this->resource = $resource;
    }

    public function path(): string
    {
        return $this->resource->queryCollection->qualifiedFilename();
    }

    public function fileContents(): string
    {
        return view($this->resource->templateView('collection'), ['resource' => $this->resource])->render();
    }
}
