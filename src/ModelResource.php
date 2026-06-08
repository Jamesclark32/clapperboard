<?php

namespace Clapperbrd\Codegen;

use Clapperbrd\Codegen\ModelResources\DataDictionary;
use Clapperbrd\Codegen\ModelResources\Factory;
use Clapperbrd\Codegen\ModelResources\Migration;
use Clapperbrd\Codegen\ModelResources\Model;
use Clapperbrd\Codegen\ModelResources\NovaResource;
use Clapperbrd\Codegen\ModelResources\Policy;
use Clapperbrd\Codegen\ModelResources\Seeder;
use Clapperbrd\Codegen\ModelResources\SeederConfig;
use Illuminate\Support\Collection;

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

    public function isQuery(): bool
    {
        return Verbs::isQuery($this->verb);
    }

    public function isCommand(): bool
    {
        return Verbs::isCommand($this->verb);
    }
}
