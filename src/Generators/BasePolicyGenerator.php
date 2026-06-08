<?php

namespace Clapperbrd\Codegen\Generators;

use Clapperbrd\Codegen\ModelResource;

class BasePolicyGenerator
{
    public function __construct(private ModelResource $resource) {}

    public function path(): string
    {
        return 'app/Policies/BasePolicy.php';
    }

    public function fileContents(): string
    {
        return view('clapperbrd-codegen::templates.base_policy')->render();
    }
}
