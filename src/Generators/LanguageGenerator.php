<?php

namespace Clapperbrd\Codegen\Generators;

use Clapperbrd\Codegen\Resource;

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
