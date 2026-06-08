<?php

namespace Clapperbrd\Codegen\Generators;

use Clapperbrd\Codegen\Resource;

class ControllerGenerator
{
    private Resource $resource;

    public function __construct(Resource $resource)
    {
        $this->resource = $resource;
    }

    public function path(): string
    {
        return $this->resource->controller->qualifiedFilename();
    }

    public function fileContents(): string
    {
        return view($this->resource->templateView('controller'), ['resource' => $this->resource])->render();
    }
}
