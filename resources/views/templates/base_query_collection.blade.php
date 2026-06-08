@php echo '<?php'.PHP_EOL @endphp

namespace App\Http\Collections;

use Illuminate\Support\Collection;

abstract class BaseQueryCollection extends Collection
{
    /**
     * Get the TypeScript interface definition for this collection.
     */
    abstract public static function getTypeScriptInterface(): string;

    /**
     * Get the expected structure/schema of items in this collection.
     */
    abstract public static function getItemSchema(): array;

    /**
     * Convert the collection to a structure that matches the TypeScript interface.
     */
    public function toTypedArray(): array
    {
        return $this->map(function ($item) {
            return $this->transformItem($item);
        })->all();
    }

    /**
     * Transform a single item to match the expected schema.
     * Override in child classes for custom transformation logic.
     */
    protected function transformItem($item): array
    {
        if (is_object($item) && method_exists($item, 'toArray')) {
            return $item->toArray();
        }

        return (array) $item;
    }

    /**
     * Create a new collection instance with type checking.
     */
    public static function make($items = []): static
    {
        $collection = new static($items);
        $collection->validateSchema();

        return $collection;
    }

    /**
     * Validate that items match the expected schema.
     */
    protected function validateSchema(): void
    {
        // Override in child classes for strict validation
        // This could check that required fields exist, types match, etc.
    }

    /**
     * Get the Wayfinder route function name for this collection.
     */
    public static function getWayfinderFunction(): ?string
    {
        // Override in child classes to specify the corresponding Wayfinder function
        return null;
    }

    /**
     * Get metadata about this collection for frontend consumption.
     */
    public function getMetadata(): array
    {
        return [
            'type' => static::class,
            'count' => $this->count(),
            'schema' => static::getItemSchema(),
            'wayfinder_function' => static::getWayfinderFunction(),
        ];
    }
}