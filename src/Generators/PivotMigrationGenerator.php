<?php

namespace Clapperbrd\Codegen\Generators;

use Clapperbrd\Codegen\ModelRelationship;
use Clapperbrd\Codegen\ModelResource;
use Clapperbrd\Codegen\ModelResources\PivotMigration;

class PivotMigrationGenerator
{
    private ModelResource $resource;

    private PivotMigration $pivotMigration;

    public function __construct(ModelResource $resource, ModelRelationship $relationship)
    {
        $this->resource = $resource;
        $this->pivotMigration = new PivotMigration($resource->resource, $relationship);
    }

    public function path(): string
    {
        return $this->pivotMigration->qualifiedFilename();
    }

    public function fileContents(): string
    {
        return view('clapperbrd-codegen::templates.pivot_migration', [
            'resource' => $this->resource,
            'pivotMigration' => $this->pivotMigration,
        ])->render();
    }
}
