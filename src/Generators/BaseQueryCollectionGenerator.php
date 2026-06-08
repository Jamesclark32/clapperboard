<?php

namespace Clapperbrd\Codegen\Generators;

use Illuminate\Support\Facades\File;

class BaseQueryCollectionGenerator
{
    public function path(): string
    {
        return 'app/Http/Collections/BaseQueryCollection.php';
    }

    public function fileContents(): string
    {
        return view('clapperbrd-codegen::templates.base_query_collection')->render();
    }

    public function shouldGenerate(): bool
    {
        return ! File::exists(base_path($this->path()));
    }
}
