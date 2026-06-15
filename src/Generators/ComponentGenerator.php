<?php

namespace JamesClark32\Clapperboard\Generators;

use JamesClark32\Clapperboard\Resource;

class ComponentGenerator
{
    private Resource $resource;

    public function __construct(Resource $resource)
    {
        $this->resource = $resource;
    }

    public function path(): ?string
    {
        return $this->resource->isQuery()
            ? $this->resource->component->qualifiedFilename()
            : null;
    }

    public function fileContents(): ?string
    {
        return $this->resource->isQuery()
            ? view($this->resource->templateView('component'), ['resource' => $this->resource])->render()
            : null;
    }
}
