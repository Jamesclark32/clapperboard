<?php

namespace JamesClark32\Clapperboard\Generators;

use JamesClark32\Clapperboard\ModelResource;

class BasePolicyGenerator
{
    public function __construct(private ModelResource $resource) {}

    public function path(): string
    {
        return 'app/Policies/BasePolicy.php';
    }

    public function fileContents(): string
    {
        return view('clapperboard::templates.base_policy')->render();
    }
}
