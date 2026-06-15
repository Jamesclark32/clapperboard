<?php

namespace JamesClark32\Clapperboard\Generators;

use Illuminate\Support\Facades\File;

class BaseQueryGenerator
{
    public function path(): string
    {
        return 'app/Http/Queries/BaseQuery.php';
    }

    public function fileContents(): string
    {
        return view('clapperboard::templates.base_query')->render();
    }

    public function shouldGenerate(): bool
    {
        return ! File::exists(base_path($this->path()));
    }
}
