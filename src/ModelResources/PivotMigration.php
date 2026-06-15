<?php

namespace JamesClark32\Clapperboard\ModelResources;

use Illuminate\Support\Str;
use JamesClark32\Clapperboard\ModelRelationship;

class PivotMigration
{
    public string $resourceName;

    public ModelRelationship $relationship;

    public function __construct(string $resourceName, ModelRelationship $relationship)
    {
        $this->resourceName = $resourceName;
        $this->relationship = $relationship;
    }

    public function relationshipClass(): string
    {
        return Str::of($this->relationship->model)->studly()->singular();
    }

    public function qualifiedFilename(): string
    {
        $tableName = $this->tableName();
        $pattern = base_path("database/migrations/*_create_{$tableName}_pivot_table.php");
        $existing = glob($pattern);

        if (! empty($existing)) {
            return str_replace(base_path('/'), '', $existing[0]);
        }

        $segments = collect([
            'database',
            'migrations',
            Str::of($tableName)->prepend(date('Y_m_d_His').'_create_')->append('_pivot_table.php'),
        ]);

        return $segments->implode('/');
    }

    public function tableName(): string
    {
        return collect([
            Str::of($this->resourceName)->singular()->snake(),
            Str::of($this->relationship->model)->singular()->snake(),
        ])
            ->sort()
            ->values()
            ->implode('_');
    }
}
