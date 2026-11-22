<?php

namespace JamesClark32\Clapperboard;

use Illuminate\Support\Collection;
use JamesClark32\Clapperboard\ModelResources\DataDictionary;
use JamesClark32\Clapperboard\ModelResources\Factory;
use JamesClark32\Clapperboard\ModelResources\Migration;
use JamesClark32\Clapperboard\ModelResources\Model;
use JamesClark32\Clapperboard\ModelResources\NovaResource;
use JamesClark32\Clapperboard\ModelResources\Policy;
use JamesClark32\Clapperboard\ModelResources\Seeder;
use JamesClark32\Clapperboard\ModelResources\SeederConfig;

class ModelResource
{
    public Model $model;

    public Migration $migration;

    public string $resource;

    public Collection $attributes;

    public Factory $factory;

    public Seeder $seeder;

    public SeederConfig $seederConfig;

    public Policy $policy;

    public NovaResource $novaResource;

    public DataDictionary $dataDictionary;

    public Collection $relationships;

    public string $description;

    public bool $softDeletes;

    public function __construct(string $resource, Collection $attributes, Collection $relationships, string $description, bool $softDeletes = false)
    {
        $this->resource = $resource;
        $this->attributes = $attributes;
        $this->relationships = $relationships;
        $this->description = $description;
        $this->softDeletes = $softDeletes;

        $this->model = new Model($resource, $attributes);
        $this->migration = new Migration($resource, $attributes);
        $this->factory = new Factory($resource, $attributes);
        $this->seeder = new Seeder($resource, $attributes);
        $this->seederConfig = new SeederConfig($resource, $attributes);
        $this->policy = new Policy($resource, $attributes);
        $this->novaResource = new NovaResource($resource);
        $this->dataDictionary = new DataDictionary($resource, $attributes);
    }

    /**
     * Attributes that get their own column, leaving out foreign keys a belongsTo
     * relationship already creates.
     */
    public function columnAttributes(): Collection
    {
        $foreignKeys = $this->relationships
            ->where('type', 'belongsTo')
            ->map(fn (ModelRelationship $relationship): string => $relationship->foreignKeyColumn());

        return $this->attributes
            ->reject(fn (ModelAttribute $attribute): bool => $foreignKeys->contains($attribute->name))
            ->values();
    }

    /**
     * Whether the attribute declared for a belongsTo's foreign key is nullable.
     */
    public function belongsToIsNullable(ModelRelationship $relationship): bool
    {
        return $this->attributes
            ->first(fn (ModelAttribute $attribute): bool => $attribute->name === $relationship->foreignKeyColumn())
            ?->nullable === true;
    }

    public function isTeamOwned(): bool
    {
        return $this->belongsTo('Team');
    }

    public function isOwned(): bool
    {
        return ! $this->isTeamOwned() && $this->belongsTo('User');
    }

    private function belongsTo(string $model): bool
    {
        return $this->relationships->contains(
            fn (ModelRelationship $relationship): bool => $relationship->type === 'belongsTo' && $relationship->model === $model
        );
    }

    public function isQuery(): bool
    {
        return Verbs::isQuery($this->verb);
    }

    public function isCommand(): bool
    {
        return Verbs::isCommand($this->verb);
    }
}
