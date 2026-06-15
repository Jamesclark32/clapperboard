<?php

namespace JamesClark32\Clapperboard\Generators;

use JamesClark32\Clapperboard\ModelResource;

class DataDictionaryGenerator
{
    private ModelResource $resource;

    public function __construct(ModelResource $resource)
    {
        $this->resource = $resource;
    }

    public function path(): string
    {
        return $this->resource->dataDictionary->qualifiedFilename();
    }

    public function fileContents(): string
    {
        return view('clapperboard::templates.data_dictionary', ['resource' => $this->resource])->render();
    }
}
