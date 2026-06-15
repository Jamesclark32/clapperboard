<?php

namespace JamesClark32\Clapperboard\Generators;

use JamesClark32\Clapperboard\Resource;

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
