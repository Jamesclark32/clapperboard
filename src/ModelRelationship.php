<?php

namespace JamesClark32\Clapperboard;

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
        foreach (['type', 'model'] as $key) {
            if (! is_string(data_get($attributes, $key))) {
                throw new \InvalidArgumentException("Each relationship needs a \"{$key}\" key, such as {\"type\":\"belongsTo\",\"model\":\"Team\"}. Got: ".json_encode($attributes));
            }
        }

        $this->type = data_get($attributes, 'type');
        $this->model = data_get($attributes, 'model');
        $this->foreignKey = data_get($attributes, 'foreignKey');
        $this->localKey = data_get($attributes, 'localKey');
        $this->pivot = data_get($attributes, 'pivot');
        $this->morphName = data_get($attributes, 'morphName');
    }

    /**
     * The column a belongsTo relationship stores its key in.
     */
    public function foreignKeyColumn(): string
    {
        return $this->foreignKey ?? \Illuminate\Support\Str::snake($this->model).'_id';
    }
}
