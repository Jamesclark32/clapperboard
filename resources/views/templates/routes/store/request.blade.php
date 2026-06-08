@php echo '<?php'.PHP_EOL @endphp

namespace {{ $resource->formRequest->namespace() }};

use App\Models\{{ str($resource->resource)->studly()->singular() }};
use Illuminate\Foundation\Http\FormRequest;

final readonly class {{ $resource->formRequest->className() }} extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->can('store', {{ str($resource->resource)->studly()->singular() }}::class);
    }

    public function rules(): array
    {
        return [
@forelse($resource->writableFillable() as $field)
            '{{ $field }}' => [
                @if($field === 'name')'required'@else'nullable'@endif,
                'string',
                'max:255',
            ],
@empty
            //
@endforelse
        ];
    }
}
