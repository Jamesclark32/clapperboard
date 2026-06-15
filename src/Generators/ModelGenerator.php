<?php

namespace JamesClark32\Clapperboard\Generators;

use JamesClark32\Clapperboard\ModelResource;

class ModelGenerator
{
    private ModelResource $resource;

    public function __construct(ModelResource $resource)
    {
        $this->resource = $resource;
    }

    public function path(): string
    {
        return $this->resource->model->qualifiedFilename();
    }

    public function fileContents(): string
    {
        return view('clapperboard::templates.model', ['resource' => $this->resource])->render();
    }
}
