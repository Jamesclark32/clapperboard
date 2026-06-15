<?php

namespace JamesClark32\Clapperboard;

use JamesClark32\Clapperboard\Support\FakerFieldMapper;

class ModelAttribute
{
    public string $name;

    public bool $fillable;

    public bool $nullable;

    public bool $indexed;

    public ?string $casts;

    public string $columnType;

    public string $fakerMethod;

    public ?array $fakerParameters;

    public ?array $compoundIndexWith;

    public ?string $default;

    public bool $unique;

    public ?int $length;

    public string $javascriptType;

    public ?string $comment;

    public function __construct(array $attributes = [])
    {
        $this->name = data_get($attributes, 'name');
        $this->fillable = (bool) data_get($attributes, 'fillable', true);
        $this->casts = data_get($attributes, 'casts', null);
        $this->columnType = data_get($attributes, 'columnType', 'string');
        $this->nullable = (bool) data_get($attributes, 'nullable', false);
        $this->indexed = (bool) data_get($attributes, 'indexed', false);

        // Use intelligent faker mapping instead of default 'word'
        $fakerMethod = data_get($attributes, 'fakerMethod');
        if (! $fakerMethod) {
            $fakerMethod = FakerFieldMapper::getFakerMethod($this->name, $this->casts);
        }
        $this->fakerMethod = $fakerMethod;

        $this->fakerParameters = data_get($attributes, 'fakerParameters', null);
        $this->compoundIndexWith = data_get($attributes, 'compoundIndexWith', null);
        $this->default = data_get($attributes, 'default', null);
        $this->comment = data_get($attributes, 'comment', null);
        $this->unique = (bool) data_get($attributes, 'unique', false);
        $this->length = data_get($attributes, 'length', null);
        $this->javascriptType = data_get($attributes, 'javascriptType', 'string');
    }

    public function defaultValue(): string
    {
        $unquoted = ['boolean', 'bool', 'integer', 'bigInteger', 'smallInteger', 'tinyInteger', 'float', 'double', 'decimal', 'unsignedBigInteger', 'unsignedInteger'];

        if (in_array($this->columnType, $unquoted)) {
            return $this->default;
        }

        return "'{$this->default}'";
    }

    public function fakerParameterString(): string
    {
        if (empty($this->fakerParameters)) {
            return '';
        }

        return collect($this->fakerParameters)->map(function ($param) {
            if (is_bool($param)) {
                return $param ? 'true' : 'false';
            }
            if (is_numeric($param)) {
                return $param;
            }

            return "'{$param}'";
        })->implode(', ');
    }
}
