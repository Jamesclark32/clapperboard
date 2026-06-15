<?php

namespace JamesClark32\Clapperboard\Generators;

use JamesClark32\Clapperboard\ModelResource;

class MigrationGenerator
{
    private ModelResource $resource;

    public function __construct(ModelResource $resource)
    {
        $this->resource = $resource;
    }

    public function path(): string
    {
        return $this->resource->migration->qualifiedFilename();
    }

    public function fileContents(): string
    {
        return view('clapperboard::templates.migration', ['resource' => $this->resource])->render();
    }
}
