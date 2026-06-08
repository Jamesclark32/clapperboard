<?php

namespace Clapperbrd\Codegen\Generators;

use Clapperbrd\Codegen\ModelRelationship;
use Clapperbrd\Codegen\ModelResource;
use Clapperbrd\Codegen\ModelResources\PivotModel;

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
        return view('clapperbrd-codegen::templates.pivot_model', [
            'pivotModel' => $this->pivotModel,
        ])->render();
    }
}
