<?php

namespace JamesClark32\Clapperboard\Generators;

use JamesClark32\Clapperboard\ModelResource;

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
        return view('clapperboard::templates.seeder', ['resource' => $this->resource])->render();
    }
}
