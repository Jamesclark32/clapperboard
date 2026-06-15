<?php

namespace JamesClark32\Clapperboard\Generators;

use JamesClark32\Clapperboard\Resource;

class BrowserTestGenerator
{
    private Resource $resource;

    public function __construct(Resource $resource)
    {
        $this->resource = $resource;
    }

    public function path(): ?string
    {
        return $this->resource->isQuery()
            ? $this->resource->browserTest->qualifiedFilename()
            : null;
    }

    public function fileContents(): ?string
    {
        return $this->resource->isQuery()
            ? view($this->resource->templateView('browser_test'), ['resource' => $this->resource])->render()
            : null;
    }
}
