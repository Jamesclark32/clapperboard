@php echo '<?php'.PHP_EOL @endphp

namespace {{ $resource->formRequest->namespace() }};

use Illuminate\Foundation\Http\FormRequest;

final class {{ $resource->formRequest->className() }} extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->can('update', $this->route('{{ $resource->routeParameterKey() }}'));
    }

    /**

     * @return array<string, array<int, string>>

     */

    public function rules(): array
    {
        return [
@forelse($resource->writableFillable() as $field)
            '{{ $field }}' => [
                @if($field === 'name')'required'@else'nullable'@endif,
                {!! $resource->typeRules($field) !!},
            ],
@empty
            //
@endforelse
        ];
    }
}
