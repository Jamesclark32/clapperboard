<?php

namespace JamesClark32\Clapperboard\ModelResources;

use Illuminate\Support\Str;
use JamesClark32\Clapperboard\ModelRelationship;

class PivotModel
{
    public string $resourceName;

    public ModelRelationship $relationship;

    public function __construct(string $resourceName, ModelRelationship $relationship)
    {
        $this->resourceName = $resourceName;
        $this->relationship = $relationship;
    }

    public function className(): string
    {
        $modelPivots = collect([
            Str::of($this->resourceName)->studly()->singular(),
            Str::of($this->relationship->model)->studly()->singular(),
        ])
            ->sort();

        return $modelPivots->join(null);
    }

    public function namespace(): string
    {
        $segments = collect([
            'App',
            'Models',
            'Pivots',
        ]);

        return $segments->implode('\\');
    }

    public function qualifiedFilename(): string
    {
        $segments = collect([
            'App',
            'Models',
            'Pivots',
        ]);

        $modelPivots = collect([
            Str::of($this->resourceName)->studly()->singular(),
            Str::of($this->relationship->model)->studly()->singular(),
        ])
            ->sort();

        $segments = $segments->push($modelPivots->join(null).'.php');

        return $segments->implode('/');
    }
}
