<?php

namespace JamesClark32\Clapperboard\Generators;

use JamesClark32\Clapperboard\Resource;

class FormComponentGenerator
{
    private Resource $resource;

    public function __construct(Resource $resource)
    {
        $this->resource = $resource;
    }

    public function path(): ?string
    {
        return in_array($this->resource->verb, ['create', 'edit'], true)
            ? $this->resource->component->qualifiedFormFilename()
            : null;
    }

    public function fileContents(): ?string
    {
        return in_array($this->resource->verb, ['create', 'edit'], true)
            ? view('clapperboard::templates.form_component', ['resource' => $this->resource])->render()
            : null;
    }
}
