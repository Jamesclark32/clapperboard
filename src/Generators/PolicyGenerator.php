<?php

namespace JamesClark32\Clapperboard\Generators;

use JamesClark32\Clapperboard\ModelResource;

class PolicyGenerator
{
    private ModelResource $resource;

    public function __construct(ModelResource $resource)
    {
        $this->resource = $resource;
    }

    public function path(): string
    {
        return $this->resource->policy->qualifiedFilename();
    }

    public function fileContents(): string
    {
        return view('clapperboard::templates.policy', ['resource' => $this->resource])->render();
    }
}
