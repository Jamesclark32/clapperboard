<?php

namespace JamesClark32\Clapperboard\ModelResources;

use Illuminate\Support\Collection;
use Illuminate\Support\Str;

class Migration
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
        $tableName = Str::of($this->resourceName)->snake()->plural();
        $pattern = base_path("database/migrations/*_create_{$tableName}_table.php");
        $existing = glob($pattern);

        if (! empty($existing)) {
            return str_replace(base_path('/'), '', $existing[0]);
        }

        $segments = collect([
            'database',
            'migrations',
            Str::of($this->resourceName)->snake()->plural()->prepend(date('Y_m_d_His').'_create_')->append('_table.php'),
        ]);

        return $segments->implode('/');
    }

    public function tableName(): string
    {
        return Str::of($this->resourceName)->plural()->snake();
    }
}
