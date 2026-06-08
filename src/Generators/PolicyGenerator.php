<?php

namespace Clapperbrd\Codegen\Generators;

use Clapperbrd\Codegen\ModelResource;

class PolicyGenerator
{
    private ModelResource $resource;

    public function __construct(ModelResource $resource)
    {
        $this->resource = $resource;
    }

    public function path(): string
    {
        return $this->resource->policy->qualifiedFilename();
    }

    public function fileContents(): string
    {
        return view('clapperbrd-codegen::templates.policy', ['resource' => $this->resource])->render();
    }
}
