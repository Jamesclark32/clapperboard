<?php

namespace Clapperbrd\Codegen;

class ModelRelationship
{
    public string $type;

    public string $model;

    public ?string $foreignKey;

    public ?string $localKey;

    public ?array $pivot;

    public ?string $morphName;

    public function __construct(array $attributes = [])
    {
        $this->type = data_get($attributes, 'type');
        $this->model = data_get($attributes, 'model');
        $this->foreignKey = data_get($attributes, 'foreignKey');
        $this->localKey = data_get($attributes, 'localKey');
        $this->pivot = data_get($attributes, 'pivot');
        $this->morphName = data_get($attributes, 'morphName');
    }
}
