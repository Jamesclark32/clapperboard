<?php

namespace Clapperbrd\Codegen\Generators;

use Clapperbrd\Codegen\Resource;

class FormRequestGenerator
{
    private Resource $resource;

    public function __construct(Resource $resource)
    {
        $this->resource = $resource;
    }

    public function path(): ?string
    {
        return in_array($this->resource->verb, ['store', 'update'])
            ? $this->resource->formRequest->qualifiedFilename()
            : null;
    }

    public function fileContents(): ?string
    {
        return in_array($this->resource->verb, ['store', 'update'])
            ? view($this->resource->templateView('request'), ['resource' => $this->resource])->render()
            : null;
    }
}
