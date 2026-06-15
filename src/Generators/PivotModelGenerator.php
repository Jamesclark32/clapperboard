<?php

namespace JamesClark32\Clapperboard\Generators;

use JamesClark32\Clapperboard\ModelRelationship;
use JamesClark32\Clapperboard\ModelResource;
use JamesClark32\Clapperboard\ModelResources\PivotModel;

class PivotModelGenerator
{
    private PivotModel $pivotModel;

    public function __construct(ModelResource $resource, ModelRelationship $relationship)
    {
        $this->pivotModel = new PivotModel($resource->resource, $relationship);
    }

    public function path(): string
    {
        return $this->pivotModel->qualifiedFilename();
    }

    public function fileContents(): string
    {
        return view('clapperboard::templates.pivot_model', [
            'pivotModel' => $this->pivotModel,
        ])->render();
    }
}
