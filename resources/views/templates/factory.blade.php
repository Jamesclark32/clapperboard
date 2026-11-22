@php echo '<?php'.PHP_EOL @endphp

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @@extends Factory<\App\Models\{{ $resource->model->className() }}>
 */
class {{ $resource->factory->className() }} extends Factory
{
    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
@foreach($resource->relationships->where('type', 'belongsTo') as $relationship)
            '{{ $relationship->foreignKeyColumn() }}' => \App\Models\{{ $relationship->model }}::factory(),
@endforeach
@foreach($resource->columnAttributes() as $attribute)
            '{{ $attribute->name }}' => fake()->{!! str_contains($attribute->fakerMethod, '(') ? $attribute->fakerMethod : $attribute->fakerMethod.'('.$attribute->fakerParameterString().')' !!},
@endforeach
        ];
    }
}
