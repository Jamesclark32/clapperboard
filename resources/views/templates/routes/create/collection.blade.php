@php echo '<?php'.PHP_EOL @endphp

namespace App\Http\Collections\{{ str($resource->resource)->studly()->plural() }};

use App\Http\Collections\BaseQueryCollection;

class {{ str($resource->resource)->studly()->plural() }}CreateCollection extends BaseQueryCollection
{
    /**
     * Get the TypeScript interface definition for this collection.
     */
    public static function getTypeScriptInterface(): string
    {
        return <<<'TS'
interface {{ str($resource->resource)->studly()->singular() }}CreateFormData {
@foreach($resource->writableFillable() as $field)
  {{ $field }}: {{ self::phpToTypeScriptType($resource->attributes->where('name', $field)->first()?->casts ?? 'string') }} | null;
@endforeach
}

interface {{ str($resource->resource)->studly()->singular() }}CreateData {
  form: {{ str($resource->resource)->studly()->singular() }}CreateFormData;
  options: {
    // Add any select options, related models, etc.
    [key: string]: any;
  };
}
TS;
    }

    /**
     * Get the expected structure/schema of items in this collection.
     */
    public static function getItemSchema(): array
    {
        return [
            'form' => [
@foreach($resource->writableFillable() as $field)
                '{{ $field }}' => '{{ $resource->attributes->where('name', $field)->first()?->casts ?? 'string' }}|null',
@endforeach
            ],
            'options' => 'array',
        ];
    }

    /**
     * Get the Wayfinder route function name for this collection.
     */
    public static function getWayfinderFunction(): string
    {
        return '{{ str($resource->resource)->camel()->plural() }}Create';
    }

    /**
     * Create empty form data for new resource.
     */
    public static function forNewResource(array $options = []): array
    {
        return [
            'form' => [
@foreach($resource->writableFillable() as $field)
                '{{ $field }}' => null,
@endforeach
            ],
            'options' => $options,
        ];
    }

    /**
     * Transform form data with validation rules applied.
     */
    protected function transformItem($item): array
    {
        if (is_array($item)) {
            return $item;
        }

        return parent::transformItem($item);
    }

    /**
     * Convert PHP type to TypeScript type.
     */
    private static function phpToTypeScriptType(string $phpType): string
    {
        return match($phpType) {
            'integer', 'int' => 'number',
            'boolean', 'bool' => 'boolean',
            'datetime', 'timestamp' => 'string',
            'json', 'array' => 'any[]',
            default => 'string',
        };
    }
}