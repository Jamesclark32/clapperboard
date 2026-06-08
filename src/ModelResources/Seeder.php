<?php

namespace Clapperbrd\Codegen\ModelResources;

use Illuminate\Support\Collection;
use Illuminate\Support\Str;

class Seeder
{
    public string $resourceName;

    public Collection $attributes;

    public function __construct(string $resourceName, Collection $attributes)
    {
        $this->resourceName = $resourceName;
        $this->attributes = $attributes;
    }

    public function qualifiedFilename(): string
    {
        $segments = collect([
            'database',
            'seeders',
            Str::of($this->resourceName)->studly()->singular()->append('Seeder.php'),
        ]);

        return $segments->implode('/');
    }

    public function className(): string
    {
        return Str::of($this->resourceName)->singular()->studly()->append('Seeder');
    }

    public function configFileName(): string
    {
        return Str::of($this->resourceName)->singular()->snake()->append('.php');
    }

    public function configName(): string
    {
        return Str::of($this->resourceName)->singular()->snake();
    }
}
