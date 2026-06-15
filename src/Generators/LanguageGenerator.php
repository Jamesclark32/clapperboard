<?php

namespace JamesClark32\Clapperboard\Generators;

use JamesClark32\Clapperboard\Resource;

class LanguageGenerator
{
    private Resource $resource;

    public function __construct(Resource $resource)
    {
        $this->resource = $resource;
    }

    public function path(): string
    {
        return $this->resource->language->qualifiedFilename();
    }

    public function fileContents(): string
    {
        return view($this->resource->templateView('language'), ['resource' => $this->resource])->render();
    }
}
