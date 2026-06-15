<?php

namespace JamesClark32\Clapperboard\Generators;

use JamesClark32\Clapperboard\Resource;

class BaseControllerGenerator
{
    private Resource $resource;

    public function __construct(Resource $resource)
    {
        $this->resource = $resource;
    }

    public function path(): string
    {
        return $this->resource->controller->qualifiedBaseFilename();
    }

    public function fileContents(): string
    {
        return view('clapperboard::templates.base_controller', ['resource' => $this->resource])->render();
    }
}
