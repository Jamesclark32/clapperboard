<?php

namespace JamesClark32\Clapperboard;

use Illuminate\Support\Facades\File;

readonly class PlannedChange
{
    public function __construct(
        public string $path,
        public string $contents,
        public string $operation,
        public bool $changed,
    ) {}

    public static function fromContents(string $path, string $contents): self
    {
        $absolutePath = base_path($path);

        if (! File::exists($absolutePath)) {
            return new self($path, $contents, 'create', true);
        }

        if (File::get($absolutePath) === $contents) {
            return new self($path, $contents, 'noop', false);
        }

        return new self($path, $contents, 'update', true);
    }

    public function absolutePath(): string
    {
        return base_path($this->path);
    }

    public function toArray(): array
    {
        return [
            'operation' => $this->operation,
            'path' => $this->path,
            'changed' => $this->changed,
        ];
    }
}
