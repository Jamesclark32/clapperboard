<?php

namespace Clapperbrd\Codegen\Generators;

use Illuminate\Support\Facades\File;

class BaseCommandGenerator
{
    public function path(): string
    {
        return 'app/Http/Commands/BaseCommand.php';
    }

    public function fileContents(): string
    {
        return view('clapperbrd-codegen::templates.base_command')->render();
    }

    public function shouldGenerate(): bool
    {
        return ! File::exists(base_path($this->path()));
    }
}
