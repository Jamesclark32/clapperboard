<?php

namespace JamesClark32\Clapperboard\Generators;

use JamesClark32\Clapperboard\ModelRelationship;
use JamesClark32\Clapperboard\ModelResource;
use JamesClark32\Clapperboard\ModelResources\PivotMigration;

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
        return view('clapperboard::templates.pivot_migration', [
            'resource' => $this->resource,
            'pivotMigration' => $this->pivotMigration,
        ])->render();
    }
}
