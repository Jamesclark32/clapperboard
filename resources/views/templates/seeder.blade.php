@php echo '<?php'.PHP_EOL @endphp

namespace Database\Seeders;

use {{ $resource->model->qualifiedName() }};
use Illuminate\Database\Seeder;

class {{ $resource->seeder->className() }} extends Seeder
{
    public function run(): void
    {
        if (config('seeders.{{ $resource->seeder->configName() }}') !== null) {
            foreach (config('seeders.{{ $resource->seeder->configName() }}') as $data) {
@php
    $seedKeys = $resource->attributes->where('unique', true)->isNotEmpty()
        ? $resource->attributes->where('unique', true)
        : $resource->attributes;
@endphp
                // Matches on the unique columns, or on the whole row when none are unique,
                // so seeding several rows that share a value doesn't overwrite them.
                {{ $resource->model->className() }}::updateOrCreate(
                    [
@foreach($seedKeys as $attribute)
                        '{{ $attribute->name }}' => data_get($data, '{{ $attribute->name }}'),
@endforeach
                    ],
                    [
@foreach($resource->attributes->diffKeys($seedKeys) as $attribute)
                        '{{ $attribute->name }}' => data_get($data, '{{ $attribute->name }}'),
@endforeach
                    ]);
            }
        }
    }
}
