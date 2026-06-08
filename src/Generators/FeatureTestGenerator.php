<?php

namespace Clapperbrd\Codegen\Generators;

use Clapperbrd\Codegen\Resource;

class FeatureTestGenerator
{
    private Resource $resource;

    public function __construct(Resource $resource)
    {
        $this->resource = $resource;
    }

    public function path(): string
    {
        return $this->resource->featureTest->qualifiedFilename();
    }

    public function fileContents(): string
    {
        return view($this->resource->templateView('feature_test'), ['resource' => $this->resource])->render();
    }
}
