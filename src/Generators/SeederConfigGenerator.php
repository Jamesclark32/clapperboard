<?php

namespace JamesClark32\Clapperboard\Generators;

use JamesClark32\Clapperboard\ModelResource;

class SeederConfigGenerator
{
    private ModelResource $resource;

    public function __construct(ModelResource $resource)
    {
        $this->resource = $resource;
    }

    public function path(): string
    {
        return $this->resource->seederConfig->qualifiedFilename();
    }

    public function fileContents(): string
    {
        return view('clapperboard::templates.seeder_config', ['resource' => $this->resource])->render();
    }
}
