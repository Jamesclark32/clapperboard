@php echo '<?php'.PHP_EOL @endphp

namespace App\Http\Collections\{{ str($resource->resource)->studly()->plural() }};

use App\Http\Collections\BaseQueryCollection;
use App\Models\{{ str($resource->resource)->studly()->singular() }};

class {{ str($resource->resource)->studly()->plural() }}ShowCollection extends BaseQueryCollection
{
    /**
     * Get the TypeScript interface definition for this collection.
     */
    public static function getTypeScriptInterface(): string
    {
        return <<<'TS'
interface {{ str($resource->resource)->studly()->singular() }}ShowItem {
@if($resource->fillable)
@foreach($resource->fillable as $field)
  {{ $field }}: string;
@endforeach
@endif
  id: number;
  created_at: string;
  updated_at: string;
}

interface {{ str($resource->resource)->studly()->singular() }}ShowData {
  {{ str($resource->resource)->camel()->singular() }}: {{ str($resource->resource)->studly()->singular() }}ShowItem;
}
TS;
    }

    /**
     * Get the expected structure/schema of items in this collection.
     */
    public static function getItemSchema(): array
    {
        return [
@if($resource->fillable)
@foreach($resource->fillable as $field)
            '{{ $field }}' => 'string',
@endforeach
@endif
            'id' => 'integer',
            'created_at' => 'datetime',
            'updated_at' => 'datetime',
        ];
    }

    /**
     * Get the Wayfinder route function name for this collection.
     */
    public static function getWayfinderFunction(): string
    {
        return '{{ str($resource->resource)->camel()->plural() }}Show';
    }

    /**
     * Transform a model instance to match the expected schema.
     */
    protected function transformItem($item): array
    {
        if ($item instanceof {{ str($resource->resource)->studly()->singular() }}) {
            return [
@if($resource->fillable)
@foreach($resource->fillable as $field)
                '{{ $field }}' => $item->{{ $field }},
@endforeach
@endif
                'id' => $item->id,
                'created_at' => $item->created_at?->toISOString(),
                'updated_at' => $item->updated_at?->toISOString(),
            ];
        }

        return parent::transformItem($item);
    }

    /**
     * Create from a single model instance.
     */
    public static function fromModel({{ str($resource->resource)->studly()->singular() }} ${{ str($resource->resource)->camel()->singular() }}): array
    {
        $collection = static::make([${{ str($resource->resource)->camel()->singular() }}]);

        return [
            '{{ str($resource->resource)->camel()->singular() }}' => $collection->first() ? $collection->transformItem($collection->first()) : null,
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