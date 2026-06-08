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
                {{ $resource->model->className() }}::updateOrCreate(
@foreach($resource->attributes as $attribute)
@if($loop->first)
                    [
                        '{{ $attribute->name }}' => data_get($data, '{{ $attribute->name }}'),
                    ],
                    [
@else
                        '{{ $attribute->name }}' => data_get($data, '{{ $attribute->name }}'),
@endif
@endforeach
                    ]);
            }
        }
    }
}
