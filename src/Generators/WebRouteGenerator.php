<?php

namespace Clapperbrd\Codegen\Generators;

use Clapperbrd\Codegen\Resource;
use Illuminate\Support\Facades\File;

class WebRouteGenerator
{
    private Resource $resource;

    public function __construct(Resource $resource)
    {
        $this->resource = $resource;
    }

    public function path(): string
    {
        return 'routes/web.php';
    }

    public function fileContents(): string
    {
        $filename = str($this->resource->resource)->plural()->lower();
        $include = "require __DIR__.'/web/{$filename}.php';";
        $content = File::get($this->path());

        if (! str_contains($content, $include)) {
            $content = rtrim($content).PHP_EOL.PHP_EOL.$include.PHP_EOL;
        }

        return $content;
    }
}
