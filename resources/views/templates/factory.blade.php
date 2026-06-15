@php echo '<?php'.PHP_EOL @endphp

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class {{ $resource->factory->className() }} extends Factory
{
    public function definition(): array
    {
        return [
@foreach($resource->relationships->where('type', 'belongsTo') as $relationship)
            '{{ Str::snake($relationship->model) }}_id' => \App\Models\{{ $relationship->model }}::factory(),
@endforeach
@foreach($resource->attributes as $attribute)
            '{{ $attribute->name }}' => fake()->{!! str_contains($attribute->fakerMethod, '(') ? $attribute->fakerMethod : $attribute->fakerMethod.'('.$attribute->fakerParameterString().')' !!},
@endforeach
        ];
    }
}
