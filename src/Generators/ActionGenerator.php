<?php

namespace Clapperbrd\Codegen\Generators;

use Clapperbrd\Codegen\Resource;

class ActionGenerator
{
    private Resource $resource;

    public function __construct(Resource $resource)
    {
        $this->resource = $resource;
    }

    public function path(): string
    {
        return $this->resource->action->qualifiedFilename();
    }

    public function fileContents(): string
    {
        return view($this->resource->templateView('action'), ['resource' => $this->resource])->render();
    }
}
