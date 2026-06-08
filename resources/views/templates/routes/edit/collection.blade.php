@php echo '<?php'.PHP_EOL @endphp

namespace App\Http\Collections\{{ str($resource->resource)->studly()->plural() }};

use App\Http\Collections\BaseQueryCollection;
use App\Models\{{ str($resource->resource)->studly()->singular() }};

class {{ str($resource->resource)->studly()->plural() }}EditCollection extends BaseQueryCollection
{
    /**
     * Get the TypeScript interface definition for this collection.
     */
    public static function getTypeScriptInterface(): string
    {
        return <<<'TS'
interface {{ str($resource->resource)->studly()->singular() }}EditFormData {
@foreach($resource->writableFillable() as $field)
  {{ $field }}: {{ self::phpToTypeScriptType($resource->attributes->where('name', $field)->first()?->casts ?? 'string') }};
@endforeach
  id: number;
}

interface {{ str($resource->resource)->studly()->singular() }}EditData {
  {{ str($resource->resource)->camel()->singular() }}: {{ str($resource->resource)->studly()->singular() }}EditFormData;
  form: {{ str($resource->resource)->studly()->singular() }}EditFormData;
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
            '{{ str($resource->resource)->camel()->singular() }}' => [
@foreach($resource->writableFillable() as $field)
                '{{ $field }}' => '{{ $resource->attributes->where('name', $field)->first()?->casts ?? 'string' }}',
@endforeach
                'id' => 'integer',
            ],
            'form' => [
@foreach($resource->writableFillable() as $field)
                '{{ $field }}' => '{{ $resource->attributes->where('name', $field)->first()?->casts ?? 'string' }}',
@endforeach
                'id' => 'integer',
            ],
            'options' => 'array',
        ];
    }

    /**
     * Get the Wayfinder route function name for this collection.
     */
    public static function getWayfinderFunction(): string
    {
        return '{{ str($resource->resource)->camel()->plural() }}Edit';
    }

    /**
     * Transform a model instance to match the expected schema.
     */
    protected function transformItem($item): array
    {
        if ($item instanceof {{ str($resource->resource)->studly()->singular() }}) {
            return [
@foreach($resource->writableFillable() as $field)
                '{{ $field }}' => $item->{{ $field }},
@endforeach
                'id' => $item->id,
            ];
        }

        return parent::transformItem($item);
    }

    /**
     * Create from a model instance for editing.
     */
    public static function fromModel({{ str($resource->resource)->studly()->singular() }} ${{ str($resource->resource)->camel()->singular() }}, array $options = []): array
    {
        $collection = static::make([${{ str($resource->resource)->camel()->singular() }}]);
        $transformedItem = $collection->transformItem(${{ str($resource->resource)->camel()->singular() }});

        return [
            '{{ str($resource->resource)->camel()->singular() }}' => $transformedItem,
            'form' => $transformedItem, // Pre-populate form with current values
            'options' => $options,
        ];
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