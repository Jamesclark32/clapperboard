<?php

namespace JamesClark32\Clapperboard\Generators;

use Illuminate\Support\Facades\File;
use JamesClark32\Clapperboard\ModelResource;

/**
 * Adds the hasMany relation a team-owned model needs on Team, so team-scoped
 * routes can resolve the record through the team with scoped bindings.
 */
class TeamRelationGenerator
{
    private ModelResource $resource;

    public function __construct(ModelResource $resource)
    {
        $this->resource = $resource;
    }

    public function shouldGenerate(): bool
    {
        return $this->resource->isTeamOwned()
            && File::exists(base_path($this->path()))
            && ! str_contains(File::get(base_path($this->path())), 'function '.$this->relationName().'(');
    }

    public function path(): string
    {
        return 'app/Models/Team.php';
    }

    public function fileContents(): string
    {
        $contents = File::get(base_path($this->path()));
        $model = str($this->resource->resource)->studly()->singular();
        $import = 'use Illuminate\Database\Eloquent\Relations\HasMany;';

        if (! str_contains($contents, $import)) {
            $contents = preg_replace('/^namespace App\\\\Models;\n\n/m', "namespace App\\Models;\n\n{$import}\n", $contents, 1);
        }

        $method = <<<PHP

    /**
     * @return HasMany<{$model}, \$this>
     */
    public function {$this->relationName()}(): HasMany
    {
        return \$this->hasMany({$model}::class);
    }

PHP;

        return preg_replace('/\n}\s*$/', "\n".rtrim($method)."\n}\n", $contents, 1);
    }

    private function relationName(): string
    {
        return str($this->resource->resource)->camel()->plural()->toString();
    }
}
